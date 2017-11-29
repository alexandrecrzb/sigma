<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Create_midia extends CI_Migration{
    public function up()
    {
        

        //Criando campos da tabela
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'auto_increment' => TRUE,                
            ),
            'nome' => array(
                'type' => 'VARCHAR',
                'constraint' => 45
            ),
            'descricao' => array(
                'type' =>  'VARCHAR',
                'constraint' => 255
            ),
            'Arquivo' => array(
                'type' =>  'VARCHAR',
                'constraint' => 255
            )
        ));

        //Adicionando id como Primary Key
        $this->dbforge->add_key('id', TRUE);
        //Criando a tabela de usuarios
        $this->dbforge->create_table('midia');
        
    }

    public function down()
    {
        $this->dbforge->drop_table('midia');
    }
}