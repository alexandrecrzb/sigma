<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct(){
        parent::__construct();
        init_dashboard();
    }

	public function index(){
        $this->inicio();
    }
    
    public function inicio(){
       if (esta_logado(FALSE)) {
           set_tema('titulo', 'Home');
           set_tema('conteudo', '<div class="small-12 columns"><p>Escolha um menu para iniciar</p></div>');
           load_template();
       } else {
           redirect('usuarios/login');
       }      
        
    }
}

/* End of file dashboard.php */
/* Location: ./application/controllers/dashboard.php */