<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Midia extends CI_Controller{
        public function __construct(){
        parent::__construct();
        init_dashboard();
        esta_logado();
        //$this->load->model('midia_model', 'midia');   
    }

	public function index(){
		$this->gerenciar();
    }

    public function cadastrar(){
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