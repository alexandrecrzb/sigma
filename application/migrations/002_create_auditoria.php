<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Create_auditoria extends CI_Migration{
    public function up()
    {
        

        //Criando campos da tabela
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'auto_increment' => TRUE,                
            ),
            'usuario' => array(
                'type' => 'VARCHAR',
                'constraint' => 45
            ),
            'data_hora' => array(
                'type' => 'TIMESTAMP'
            ),
            'operacao' => array(
                'type' =>  'VARCHAR',
                'constraint' => 45
            ),
            'query' => array(
                'type' => 'TEXT'
            ),
            'observacao' => array(
                'type' => 'TEXT'
            )
        ));

        //Adicionando id como Primary Key
        $this->dbforge->add_key('id', TRUE);
        //Criando a tabela de usuarios
        $this->dbforge->create_table('auditoria');
        
    }

    public function down()
    {
        $this->dbforge->drop_table('auditoria');
    }
}