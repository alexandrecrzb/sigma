<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auditoria extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        init_dashboard();
        esta_logado();
        $this->load->model('auditoria_model', 'auditoria');   
    }

	public function index()
	{
		$this->gerenciar();
    }
    

    public function gerenciar(){
        set_tema('arquivo_js', load_js(array('data-table', 'table')), FALSE);
        set_tema('titulo', 'Registros de auditoria');        
        set_tema('conteudo', load_modulo('auditoria', 'gerenciar'));     
        load_template();
    }
    
}


/* End of file auditoria.php */
/* Location: ./application/controllers/auditoria.php */