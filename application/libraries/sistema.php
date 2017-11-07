<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Sistema {

  protected $CI;
  public $tema = array();

  public function __construct()
  {
    $this->CI =& get_instance();
    $this->CI->load->helper('funcoes');
  }

  public function enviar_email($destino, $assunto, $mensagem, $formato='html'){
    $this->CI->load->library('email');
    $config['mailtype'] = $formato;
    $this->CI->email->initialize($config);
    $this->CI->email->from('sistema@sinergiamedica.com.br', 'Administração do sistema');
    $this->CI->email->to($destino);
    $this->CI->email->subject($assunto);
    $this->CI->email->message($mensagem);

    //Verifica se o email foi enviado
    if ($this->CI->email->send()) {
      return TRUE;
    } else {
      return $this->CI->email->print_debugger();   
    }
    
  }
}


/* End of file sistema.php */
/* Location: ./application/libraries/sistema.php */