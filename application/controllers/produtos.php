<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Produtos extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        init_dashboard();
        esta_logado();
        $this->load->model('produtos_model', 'produtos');   
    }

	public function index()
	{
		$this->gerenciar();
    }
    

    public function gerenciar(){
        set_tema('arquivo_js', load_js(array('data-table', 'table')), FALSE);
        set_tema('titulo', 'Produtos');        
        set_tema('conteudo', load_modulo('produtos', 'gerenciar'));     
        load_template();
    }
    
}


/* End of file produtos.php */
/* Location: ./application/controllers/produtos.php */