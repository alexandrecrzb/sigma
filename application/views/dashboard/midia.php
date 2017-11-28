<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

switch ($tela) {
    case 'cadastrar':
        echo '<div class="small-12 columns">';
        echo breadcrumbs();
        echo form_open_multipart('midia/cadastrar');
        echo form_fieldset('Upload de Midia', array('class'=>'fieldset'));
        
        //Erros de validação
        erros_validacao();

        //Mostra mensagem do flashdata
        get_msg('msg_ok');

        //Campos do formulario
        echo form_label('Nome do arquivo', 'nome');
        echo form_input(array('name' => 'nome', 'id' => 'nome', 'class' => 'small-3', 'maxlenght' => '100'), set_value('nome'), 'autofocus');
        
        echo form_label('Descrição do arquivo', 'descricao');
        echo form_input(array('name' => 'descricao', 'id' => 'descricao', 'class' => 'small-3', 'maxlenght' => '100'), set_value('descricao'));
        
        echo form_label('Arquivo');
        echo form_upload(array('name' =>'arquivo' ,'class' => 'small-12'),set_value('arquivo'));

        //Botões
        echo form_submit(array('name' => 'Cadastrar','class'=> 'button'), 'Salvar'),'&nbsp';
        echo anchor ('midia/gerenciar', 'Cancelar', array('class' => 'button alert espaco-btn'));

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
                 //Se for 0 ele pega tudo na função do model pega_tudo();
                $modo = $this->uri->segment(3);
                if ($modo == 'all') {
                    $limite = 0;
                } else {
                    $limite = 15;
                    echo '<p>Mostrando os últimos 15 registros, para ver todo histórico '.anchor('auditoria/gerenciar/all','clique aqui').'</p>';
                }
            ?>            
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Usuário</th>
                        <th>Data e hora</th>
                        <th>Operação</th>
                        <th>Observação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $query = $this->auditoria->pega_tudo($limite)->result();
                        foreach ($query as $linha) {
                            echo '<tr>';
                            printf('<td>%s</td>', $linha->usuario);
                            printf('<td>%s</td>', date('d/m/Y H:i:s', strtotime($linha->data_hora)));
                            printf('<td>%s</td>', '<span data-tooltip aria-haspopup="true" class="has-tip top" data-disable-hover="false" tabindex="2" title="'.$linha->query.'">'.$linha->operacao .'</span>');
                            printf('<td>%s</td>', $linha->observacao);
                            echo '</tr>';
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <?php
        break;

    default:
        echo '<div class="callout alert"><p>Página não encontrada</p></div>';      
        break;
}