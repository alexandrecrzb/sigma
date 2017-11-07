<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

switch ($tela) {
    case 'login':
        echo '<div class="small-4 small-centered">';        
        echo form_open('usuarios/login', array('class'=>'custom log-in-form'));
        echo '<h4 align="center">Login</h4>';
        echo form_label('Usuário');
        echo form_input(array('name'=>'usuario'), set_value('usuario'), 'autofocus');
        echo form_label('Senha');
        echo form_password(array('name'=>'senha'), set_value('senha'));
        echo form_hidden('redirect', $this->session->userdata('redir_para'));
        echo form_submit(array('name' => 'logar','class'=> 'button expanded'), 'Entrar');
        // erros de validação
        erros_validacao();
        //Mostrar mensagem do flashdata
        get_msg('erro_login');
        echo '<p class="text-center">'.anchor('usuarios/nova_senha', 'Esqueci minha senha'). '</p>';
        echo form_close();
        echo '</div>';
        break;
    
        case 'nova_senha':                
        echo '<div class="small-6 small-centered">';
        echo form_open('usuarios/nova_senha', array('class'=>'log-in-form'));
        echo '<h4 align="center">Recuperação de senha</h4>';
        echo form_label('Entre com seu e-mail de cadastro para enviar-mos as instruções.');
        echo form_input(array('name'=>'email', 'placeholder' => 'Digite seu e-mail'), set_value('email'), 'autofocus');
        echo form_submit(array('name' => 'novaSenha','class'=> 'button expanded'), 'Enviar nova senha');
        // erros de validação
        erros_validacao();
        //Mostrar mensagem do flashdata
        get_msg('msg_ok');
        get_msg('msg_erro');
        echo '<p class="text-center">Ou &nbsp &nbsp'.anchor('usuarios/login', 'Voltar para página de login'). '</p>';
        echo form_close();
        echo '</div>';
        break;
        
        case 'cadastrar':
        echo '<div class="small-12 columns">';
        echo breadcrumbs();
        echo form_open('usuarios/cadastrar');
        echo form_fieldset('Cadastrar novo usuário', array('class'=>'fieldset'));
        
        //Erros de validação
        erros_validacao();

        //Mostra mensagem do flashdata
        get_msg('msg_ok');

        //Campos do formulario
        echo form_label('Nome completo', 'nome');
        echo form_input(array('name' => 'nome', 'id' => 'nome', 'class' => 'small-3', 'maxlenght' => '100'), set_value('nome'), 'autofocus');
        
        echo form_label('E-mail', 'email');
        echo form_input(array('name' => 'email', 'id' => 'email', 'class' => 'small-3', 'maxlenght' => '100'), set_value('email'));
        
        echo form_label('Login', 'login');
        echo form_input(array('name' => 'login', 'id' => 'login', 'class' => 'small-3', 'maxlenght' => '45'), set_value('login'));

        echo form_label('Senha', 'senha');
        echo form_password(array('name' => 'senha', 'id' => 'senha', 'class' => 'small-3', 'maxlenght' => '32'), set_value('senha'));

        echo form_label('Confirmar senha', 'confiraSenha');
        echo form_password(array('name' => 'confirmaSenha', 'id' => 'confirmaSenha', 'class' => 'small-3', 'maxlenght' => '32'), set_value('confirmaSenha'));

        echo form_checkbox(array('name' => 'adm', 'id' => 'adm'), '1') .'<label for="adm">Administrador</label> <br> <br>';

        //Botões
        echo form_submit(array('name' => 'Cadastrar','class'=> 'button'), 'Salvar'),'&nbsp';
        echo anchor ('/dashboard', 'Cancelar', array('class' => 'button alert espaco-btn'));

        echo form_fieldset_close();
        echo form_close();
        echo '</div>';
        break;

        case 'gerenciar':
        ?>
        <script type="text/javascript">
			$(function(){
				$('.del-reg').click(function(){
					if (confirm("Deseja realmente excluir este registro?\nEsta operação não poderá ser desfeita!")) return true; else return false;
				});
			});
		</script>

         <div class="small-12 columns">
            <?php 
                echo breadcrumbs();
                get_msg('msg_ok');
                get_msg('msg_erro');
            ?>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Login</th>
                        <th>E-mail</th>
                        <th>Ativo / Adm</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $query = $this->usuarios->pega_tudo()->result();
                        foreach ($query as $linha) {
                            echo '<tr>';
                            printf('<td>%s</td>', $linha->nome);
                            printf('<td>%s</td>', $linha->login);
                            printf('<td>%s</td>', $linha->email);
                            printf('<td>%s / %s</td>', ($linha->ativo == 0) ? 'Não' : 'Sim', ($linha->adm == 0) ? 'Não' : 'Sim');
                            printf('<td class="text-center">%s%s%s</td>', 
                                anchor("usuarios/editar/$linha->id", ' ', array('class' => 'table-actions table-edit', 'title' => 'Editar')), 
                                anchor("usuarios/alterar_senha/$linha->id", ' ', array('class' => 'table-actions table-pass', 'title' => 'Alterar Senha')),
                                anchor("usuarios/excluir/$linha->id", ' ', array('class' => 'table-actions table-delete del-reg', 'title' => 'Excluir'))
                        );
                            echo '</tr>';
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <?php
        break;

        case 'alterar_senha':
            $idUser = $this->uri->segment(3);
            if ($idUser == 0 || NULL) {
                set_msg('msg_erro', 'Escolha um usuario para alterar', 'erro');
            redirect('usuarios/gerenciar');
            }   

            ?>
            <div class="small-12 columns">
                <?php 
                echo breadcrumbs();
                    if (is_admin() || $idUser == $this->session->userdata('user_id')) {
                        $query = $this->usuarios->pegar_porId($idUser)->row();
                        echo form_open(current_url());
                        echo form_fieldset('Alterar senha', array('class'=>'fieldset'));                    
                        //Erros de validação
                        erros_validacao();
                        //Mostra mensagem do flashdata
                        get_msg('msg_ok');

                        //Campos do formulario
                        echo form_label('Usuario', 'nome');
                        echo form_input(array('name' => 'nome', 'id' => 'nome', 'class' => 'small-3', 'maxlenght' => '100', 'disabled' => 'disabled'), set_value('nome', $query->nome));
                        
                        echo form_label('Nova senha', 'senha');
                        echo form_password(array('name' => 'senha', 'id' => 'senha', 'class' => 'small-3', 'maxlenght' => '32'), set_value('senha'), 'autofocus');

                        echo form_label('Confirmar senha', 'confirmaSenha');
                        echo form_password(array('name' => 'confirmaSenha', 'id' => 'confirmaSenha', 'class' => 'small-3', 'maxlenght' => '32'), set_value('confirmaSenha'));

                        //Botões
                        echo form_submit(array('name' => 'alterarSenha','class'=> 'button'), 'Alterar'),'&nbsp';
                        echo anchor ('usuarios/gerenciar', 'Cancelar', array('class' => 'button alert espaco-btn'));
                        echo form_hidden('idUsuario', $idUser);
                        
                        echo form_fieldset_close();
                        echo form_close();
                    } else {
                        set_msg('msg_erro', 'Você não tem permissão para executar esta operação.', 'erro');
                        redirect('usuarios/gerenciar');
                    }
                    
                ?>
            </div>            
            <?php        
        break;
        
        case 'editar':                
            $idUser = $this->uri->segment(3);
            if ($idUser == 0 || NULL) {
                set_msg('msg_erro', 'Escolha um usuario para editar', 'erro');
            redirect('usuarios/gerenciar');
            }   

            ?>
            <div class="small-12 columns">
                <?php 
                echo breadcrumbs();
                    if (is_admin() || $idUser == $this->session->userdata('user_id')) {
                        $query = $this->usuarios->pegar_porId($idUser)->row();
                        echo form_open(current_url());
                        echo form_fieldset('Editar usuário', array('class'=>'fieldset'));                    
                        //Erros de validação
                        erros_validacao();
                        //Mostra mensagem do flashdata
                        get_msg('msg_ok');

                        //Campos do formulario
                        echo form_label('Nome completo', 'nome');
                        echo form_input(array('name' => 'nome', 'id' => 'nome', 'class' => 'small-3', 'maxlenght' => '100'), set_value('nome', $query->nome), 'autofocus');
                        
                        echo form_label('E-mail', 'email');
                        echo form_input(array('name' => 'email', 'id' => 'email', 'class' => 'small-3', 'maxlenght' => '100'), set_value('email', $query->email));
                        
                        echo form_label('Login', 'login');
                        echo form_input(array('name' => 'login', 'id' => 'login', 'class' => 'small-3', 'maxlenght' => '45'), set_value('login', $query->login));

                        //Checkbox de adm
                        echo form_checkbox(array('name' => 'ativo', 'id' => 'ativo'), '1', ($query->ativo == 1) ? FALSE : TRUE) .'<label for="ativo">Desativar</label> <br>';
                        echo form_checkbox(array('name' => 'adm', 'id' => 'adm'), '1', ($query->adm == 1) ? TRUE : FALSE) .'<label for="adm">Administrador</label> <br> <br>';

                        //Botões
                        echo form_submit(array('name' => 'editar','class'=> 'button'), 'Alterar'),'&nbsp';
                        echo anchor ('usuarios/gerenciar', 'Cancelar', array('class' => 'button alert espaco-btn'));
                        echo form_hidden('idUsuario', $idUser);
                        
                        echo form_fieldset_close();
                        echo form_close();
                    } else {
                        set_msg('msg_erro', 'Você não tem permissão para executar esta operação.', 'erro');
                        redirect('usuarios/gerenciar');
                    }
                    
                ?>
            </div>            
            <?php
        break;
    default:
        echo '<div class="callout alert"><p>Página não encontrada</p></div>';      
        break;
}