<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Create_usuarios extends CI_Migration{
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
                'constraint' => 100
            ),
            'email' => array(
                'type' => 'VARCHAR',
                'constraint' => 100
            ),
            'login' => array(
                'type' => 'VARCHAR',
                'constraint' => 45
            ),
            'senha' => array(
                'type' => 'VARCHAR',
                'constraint' => 32
            ),
            'ativo' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1
            ),
            'adm' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0
            )
        ));

        //Adicionando id como Primary Key
        $this->dbforge->add_key('id', TRUE);
        //Criando a tabela de usuarios
        $this->dbforge->create_table('usuarios');
        
    }

    public function down()
    {
        $this->dbforge->drop_table('usuarios');
    }
}