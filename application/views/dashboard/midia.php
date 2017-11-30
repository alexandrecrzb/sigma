<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

switch ($tela) {
    case 'cadastrar':
        echo '<div class="small-12 columns">';
        echo breadcrumbs();
        echo form_open_multipart('midia/cadastrar', array('class'=>'custom'));
       
        
        //Erros de validação
        erros_validacao();

        //Mostra mensagem do flashdata
        get_msg('msg_ok');
        get_msg('msg_erro');

        //Fieldset para organizar os forms no container
        echo form_fieldset('Upload de Midia', array('class'=>'fieldset'));
        //Campos do formulario
        echo form_label('Nome do arquivo', 'nome');
        echo form_input(array('name' => 'nome', 'id' => 'nome', 'class' => 'small-3', 'maxlenght' => '100'), set_value('nome'), 'autofocus');
        
        echo form_label('Descrição do arquivo', 'descricao');
        echo form_input(array('name' => 'descricao', 'id' => 'descricao', 'class' => 'small-3', 'maxlenght' => '100'), set_value('descricao'));
        
        echo form_label('Arquivo');
        echo form_upload(array('name' =>'arquivo' ,'class' => 'small-12'),set_value('arquivo'));

        //Botões
        echo form_submit(array('name' => 'Enviar','class'=> 'button'), 'Salvar'),'&nbsp';
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
                echo 
                breadcrumbs();
                get_msg('msg_ok');
                get_msg('msg_erro');
            ?>            
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Link</th>
                        <th>Thumbnail</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $query = $this->midia->pega_tudo()->result();
                        foreach ($query as $linha) {
                            echo '<tr>';
                            printf('<td>%s</td>', $linha->nome);
                            printf('<td>%s</td>', base_url("uploads/imagens/$linha->arquivo"));
                            printf('<td>%s</td>', 'Thumb');
                            printf('<td class="text-center">%s%s%s</td>', 
                                anchor("uploads/$linha->arquivo", ' ', array('class' => 'table-actions table-view', 'title' => 'Visualizar', 'target' => '_blank')), 
                                anchor("midia/editar/$linha->id", ' ', array('class' => 'table-actions table-edit', 'title' => 'Editar')),
                                anchor("midia/excluir/$linha->id", ' ', array('class' => 'table-actions table-delete del-reg', 'title' => 'Excluir'))
                        );
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