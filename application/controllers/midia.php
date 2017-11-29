<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Midia extends CI_Controller{
        public function __construct(){
        parent::__construct();
        init_dashboard();
        esta_logado();
        $this->load->model('midia_model', 'midia');   
    }

	public function index(){
		$this->gerenciar();
    }

    public function cadastrar(){
        $this->form_validation->set_rules('nome', '<strong>Usuário</strong>', 'trim|required|ucfirst');
        $this->form_validation->set_rules('descricao', '<strong>E-Descrição</strong>', 'trim');
        if($this->form_validation->run()==TRUE){
            $upload = $this->midia->do_upload('arquivo');
            if (is_array($upload) && $upload['file_name'] != '') {
            $dados = elements(array('nome', 'descricao'), $this->input->post());
            $dados['arquivo'] = $upload['file_name'];
            $this->midia->inserir_dados($dados);
            } else {
                set_msg('msg_erro', $upload, 'erro');
                redirect(current_url());
            }            
        }
    set_tema('titulo', 'Upload de arquivos');        
    set_tema('conteudo', load_modulo('midia', 'cadastrar'));     
    load_template();
    }    

    public function gerenciar(){
        set_tema('arquivo_js', load_js(array('data-table', 'table')), FALSE);
        set_tema('titulo', 'Registros de auditoria');        
        set_tema('conteudo', load_modulo('midia', 'gerenciar'));     
        load_template();
    }
    
}


/* End of file midia.php */
/* Location: ./application/controllers/midia.php */