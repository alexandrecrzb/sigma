<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuarios extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        init_dashboard();
    }

	public function index()
	{
		$this->gerenciar();
    }
    
    public function login()
    {
        //Validação de campos
        $this->form_validation->set_rules('usuario', '<strong>Usuário</strong>', 'trim|required|strtolower');
        $this->form_validation->set_rules('senha', '<strong>Senha</strong>', 'trim|required|strtolower');

        if ($this->form_validation->run() == TRUE) {
            $usuario = $this->input->post('usuario', TRUE);
            $senha = md5($this->input->post('senha', TRUE));
            //Redireciona para url solicitada na tela de login
            $redirect = $this->input->post('redirect', TRUE);
            if ($this->usuarios->fazer_login($usuario, $senha) == TRUE) {
                $query = $this->usuarios->pegar_porlogin($usuario)->row();
                $dados = array(
                    'user_id' => $query->id,
                    'user_nome' => $query->nome,
                    'user_admin' => $query->adm,
                    'user_logado'=> TRUE
                );
                $this->session->set_userdata($dados);
                auditoria('Login no sistema', 'Login efetuado com sucesso');
                if ($redirect != '') {
                    redirect($redirect);
                } else {
                redirect('dashboard');
                }
                

            } else {
                $query = $this->usuarios->pegar_porlogin($usuario)->row();
                if (empty($query) || $query->senha != $senha) {
                    set_msg('erro_login', 'Usuário ou senha invalidos','erro');
                } elseif ($query->ativo == 0){
                    set_msg('erro_login', 'Usuário inativo. </br> Entre em contato com o suporte.','erro');
                }
            }
            redirect('usuarios/login');
            
        } 

        //Carregar modulo de usuarios e mostrar tela de login                
        set_tema('titulo', 'Login');        
        set_tema('conteudo', load_modulo('usuarios', 'login'));
        set_tema('footer', '');
        load_template();
    }

    public function logout(){
        //auditoria('Logout do sistema', 'Logout efetuado com sucesso');
        $this->session->unset_userdata(array(
            'user_id' => '',
            'user_nome' => '',
            'user_admin' => '',
            'user_logado' => ''
        ));
        $this->session->sess_destroy();
        $this->session->sess_create();
        redirect('usuarios/login');
    }

    public function nova_senha()
    {
        //Validação de campos
        $this->form_validation->set_rules('email', '<strong>E-mail</strong>', 'trim|required|valid_email|strtolower');        
        
        //Pegando email cadastrado no banco de dados
        if ($this->form_validation->run() == TRUE) {
            $email = $this->input->post('email');
            $query = $this->usuarios->pegar_porEmail($email);
            /*Se retornar um registro
            cria uma senha randomica e envia para o email do usuario
            */
            if ($query->num_rows() == 1) {
                $novasenha = substr(str_shuffle('qwertyuiopasdfghjklzxcvbnm0123456789'), 0, 6);
                $mensagem = "<p>Sua nova senha é: <strong>$novasenha</strong></p></br><p>Troque esta senha para uma de sua preferencia</p>";
                if ($this->sistema->enviar_email($email, 'Nova senha de acesso', $mensagem)) {
                    $dados['senha'] = md5($novasenha);
                    $this->usuarios->fazer_update($dados, array('email'=>$email), FALSE);
                    set_msg('msg_ok', 'Uma nova senha foi enviada para seu email', 'sucesso');
                    auditoria('Redefinição de senha', 'Usuario solicitou uma nova senha');
                    redirect('usuarios/nova_senha');
                } else {
                    set_msg('msg_erro', 'Erro ao enviar nova senha, contate o suporte', 'erro');
                    redirect('usuarios/nova_senha');
                }
                
            } else {
                set_msg('msg_erro', 'Email não cadastrado no sistema', 'erro');
                redirect('usuarios/nova_senha');
            }
            
        } 

        //Carregar modulo de usuarios e mostrar tela de nova senha

        set_tema('titulo', 'Recuperar senha');        
        set_tema('conteudo', load_modulo('usuarios', 'nova_senha'));
        set_tema('footer', '');
        load_template();
    }

    public function cadastrar()
    {
        esta_logado();
        $this->form_validation->set_message('is_unique', '%s já está sendo usado');
        $this->form_validation->set_message('matches', 'Senhas não correspondem');
        $this->form_validation->set_rules('nome', '<strong>Usuário</strong>', 'trim|required|ucwords');
        $this->form_validation->set_rules('email', '<strong>E-mail</strong>', 'trim|required|valid_email|is_unique[usuarios.email]|strtolower');
        $this->form_validation->set_rules('login', '<strong>Login</strong>', 'trim|required|minlength[4]|is_unique[usuarios.login]|strtolower');
        $this->form_validation->set_rules('senha', '<strong>Senha</strong>', 'trim|required|minlength[4]|strtolower');
        $this->form_validation->set_rules('confirmaSenha', '<strong>Confirmar Senha</strong>', 'trim|required|minlength[4]|strtolower|matches[senha]');
        if($this->form_validation->run()==TRUE){
            $dados = elements(array('nome', 'email', 'login'), $this->input->post());
            $dados['senha'] = md5($this->input->post('senha'));
            if (is_admin()) {
                //Se o checkbox for marcado com o usuario logado com campo adm 0 ele insere 0
                $dados['adm'] = ($this->input->post('adm') == 1) ? 1 : 0;
            }
            $this->usuarios->inserir_dados($dados);
        }
        set_tema('titulo', 'Cadastrar Usuário');        
        set_tema('conteudo', load_modulo('usuarios', 'cadastrar'));
        load_template();
    }

    public function gerenciar(){
        esta_logado();   

        set_tema('arquivo_js', load_js(array('data-table', 'table')), FALSE);
        set_tema('titulo', 'Listagem de usuarios');        
        set_tema('conteudo', load_modulo('usuarios', 'gerenciar'));     
        load_template();
    }

    public function alterar_senha(){
        esta_logado();   
        $this->form_validation->set_message('matches', 'Senhas não correspondem');
        $this->form_validation->set_rules('senha', '<strong>Senha</strong>', 'trim|required|minlength[4]|strtolower');
        $this->form_validation->set_rules('confirmaSenha', '<strong>Confirmar senha</strong>', 'trim|required|minlength[4]|strtolower|matches[senha]');
        if($this->form_validation->run()==TRUE){
            $dados['senha'] = md5($this->input->post('senha'));
            $this->usuarios->fazer_update($dados, array('id'=> $this->input->post('idUsuario')));
        }
        set_tema('titulo', 'Alterar senha');        
        set_tema('conteudo', load_modulo('usuarios', 'alterar_senha'));     
        load_template();
    }

    public function editar(){
        esta_logado();
        $this->form_validation->set_rules('nome', '<strong>Usuário</strong>', 'trim|required|ucwords');
        $this->form_validation->set_rules('email', '<strong>E-mail</strong>', 'trim|required|valid_email|strtolower');
        $this->form_validation->set_rules('login', '<strong>Login</strong>', 'trim|required|minlength[4]|strtolower');
        if($this->form_validation->run()==TRUE){
            $dados = elements(array('nome', 'email', 'login'), $this->input->post());
            //$dados['ativo'] = ($this->input->post('ativo') == 1 ? 1 : 0);
            if (is_admin()) {
                //Se o checkbox for marcado com o usuario logado comsem ser adm ele insere 0
                $dados['adm'] = ($this->input->post('adm') == 1) ? 1 : 0;
                $dados['ativo'] = ($this->input->post('ativo') == 0) ? 1 : 0;
            }
            $this->usuarios->fazer_update($dados, array('id'=> $this->input->post('idUsuario')));
        }

        set_tema('titulo', 'Editar cadastro');        
        set_tema('conteudo', load_modulo('usuarios', 'editar'));     
        load_template();
    }

        public function excluir(){
        esta_logado();
        if (is_admin(TRUE)) {
            $idUser = $this->uri->segment(3);
            if ($idUser != NULL) {
                $query = $this->usuarios->pegar_porId($idUser);
                    if ($query->num_rows()==1) {
                        $query = $query->row();
                        if ($query->id != 1) {
                            $this->usuarios->deletar(array('id'=>$query->id), FALSE);
                        } else {
                           set_msg('msg_erro', 'Este usuario não pode ser excluido.', 'erro');
                        }
                        
                    } else {
                        set_msg('msg_erro', 'Usuario não encontrado.', 'erro');
                    }
                    
            } else {
                set_msg('msg_erro', 'Escolha um usuario para excluir.', 'erro');
            }
            redirect('usuarios/gerenciar');
            
        }
        
        set_tema('titulo', 'Excluir usuario');        
        set_tema('conteudo', load_modulo('usuarios', 'excluir'));     
        load_template();
    }


}


/* End of file usuarios.php */
/* Location: ./application/controllers/usuarios.php */