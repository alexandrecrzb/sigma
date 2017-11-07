<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//Carrega um módulo do sistema devolvendo a tela solicitada

function load_modulo($modulo=NULL, $tela=NULL, $diretorio='dashboard')
{
    $CI = & get_instance();
    if ($modulo != NULL) {
        return $CI->load->view("$diretorio/$modulo", array('tela'=>$tela), TRUE);
    } else {
        return FALSE;
    }
}
//seta valores ao array $tema da classe sistema
//Propriedade, valor e replace(true ou false)
function set_tema($prop, $valor, $replace=TRUE){
    $CI = & get_instance();
    $CI->load->library('sistema');
    if ($replace) {
        $CI->sistema->tema[$prop] = $valor;
    } else {
        //Por que diabos isso funciona assim?
        if(!isset($CI->sistema->tema[$prop])) $CI->sistema->tema[$prop] = '';{        
        $CI->sistema->tema[$prop] .= $valor;
        }
    }
    
}



//retorna os valores do array $tema da classe sistema
function get_tema(){
    $CI = & get_instance();
    $CI->load->library('sistema');
    return $CI->sistema->tema;
}

//inicializa o dashboard carregando os recursos necessarios
function init_dashboard(){
    $CI = & get_instance();
    $CI->load->library(array ('parser', 'sistema', 'session', 'form_validation'));
    $CI->load->helper(array('form', 'url', 'array', 'text'));
    //carregamento dos models
    $CI->load->model('usuarios_model', 'usuarios');

    set_tema('titulo_padrao', 'Sigma');
    set_tema('footer', '<p>&copy; 2017 Sinergia Médica</p>');
    set_tema('template', 'dashboard/index');

    //carregamento de folhas de estilos css
    set_tema('arquivo_css', load_css(array('foundation.min', 'app', 'foundation-icons/foundation-icons')), FALSE);
    set_tema('jquery', load_js(array('jquery')), FALSE);
    set_tema('arquivo_js', load_js(array('jquery', 'foundation.min', 'app')), FALSE);
}

//Carrega um template passando o array $tema como parâmetro
function load_template(){
    $CI = & get_instance();
    $CI->load->library('sistema');
    $CI->parser->parse($CI->sistema->tema['template'], get_tema());
}

//Carregar arquivos css de uma pasta (**REFATORAR tags mostradas lado a lado)
function load_css($arquivo = NULL, $pasta = 'css', $media='all'){
    if ($arquivo != NULL) {
        $CI = & get_instance();
        $CI->load->helper('url');
        $retorno ='';

        if (is_array($arquivo)) {
            foreach ($arquivo as $css) {
                $retorno .= '<link rel="stylesheet" type="text/css" href="'.base_url("$pasta/$css.css").'" media="'.$media.'" >'; 
            }
        } else {
            $retorno .= '<link rel="stylesheet" type="text/css" href="'.base_url("$pasta/$arquivo.css").' media="'.$media.'">';
        }        
    }

    return $retorno;
}

//Carregar um ou varios arquivos js de uma pasta ou CDN (**REFATORAR tags mostradas lado a lado)
function load_js($arquivo=NULL, $pasta='js', $remoto=FALSE){
	if ($arquivo != NULL) {
			$CI =& get_instance();
			$CI->load->helper('url');
			$retorno = '';
			if (is_array($arquivo)) {
				foreach ($arquivo as $js) {
					if ($remoto) {
						$retorno .= '<script type="text/javascript" src="'.$js.'"></script>';
					} else {
						$retorno .= '<script type="text/javascript" src="'.base_url("$pasta/$js.js").'"></script>';
					}
				}
			}
			 else {
				if ($remoto) {
					$retorno .= '<script type="text/javascript" src="'.$arquivo.'"></script>';
				} else {
					$retorno .= '<script type="text/javascript" src="'.base_url("$pasta/$arquivo.js").'"></script>';
				}
			}
		}
	return $retorno;
}


function erros_validacao(){
	if (validation_errors()) {
		echo '<div class="callout alert small">'.validation_errors('<p>','</p>').'</div>';
	}
}

//Verifica se usuario esta logado no sistema
function esta_logado($redir = TRUE){
    $CI = & get_instance();
    $CI->load->library('session');
    $user_status = $CI->session->userdata('user_logado');
    if (!isset($user_status) || $user_status != TRUE) {

        if ($redir) {
            $CI->session->set_userdata(array('redir_para'=>current_url()));
            set_msg('erro_login', 'Acesso restrito, faça login antes de prosseguir', 'erro');
            redirect('usuarios/login');
        } else {
            return FALSE;
        }        
    } else {
        return TRUE;
    }
    
}

//Define uma mensagem para ser exibida na proxima pagina carregada (sessoes flash)
//Parametros: id da msg, texto da msg, tipo da msg (erro ou sucesso)
function set_msg($id = 'msg_erro', $msg = NULL, $tipo = ''){
    $CI = & get_instance();
    switch ($tipo) {
        case 'erro':
            $CI->session->set_flashdata($id, '<div class="callout alert small"><p>'.$msg.'</p></div>');
            break;
        case 'sucesso':
            $CI->session->set_flashdata($id, '<div class="callout success small"><p>'.$msg.'</p></div>');
            break;
        
        default:
            $CI->session->set_flashdata($id, '<div class="callout small"><p>'.$msg.'</p></div>');
            break;
    }
}

//Verifica se existe uma mensagem para ser exibida na página atual e Exibe na view
function get_msg($id, $mostrar = TRUE){
    $CI = & get_instance();
    if ($CI->session->flashdata($id)){
        if ($mostrar) {
            echo $CI->session->flashdata($id);
            return TRUE;
        } else {
            return $CI->session->flashdata($id);
        }
        return FALSE;
    }
}

//Verifica se o usuario atual é administrador
//Alem de ser adm ou nao é possivel mostrar uma msg na sessao
//
function is_admin($set_msg = FALSE){
    $CI = & get_instance();
    $user_admin = $CI->session->userdata('user_admin');
    //
    if (!isset($user_admin) || $user_admin != TRUE) {
        if ($set_msg) {
            set_msg('msg_erro', 'Você não tem permissão para executar esta operação.', 'erro');
            return FALSE;
        }
    } else {
        return TRUE;
    }    
}

//Gerador de breadcrumbs do site com base no controller
function breadcrumbs(){
    $CI =& get_instance();
	$CI->load->helper('url');
    $classe = ucfirst($CI->router->class);
    if ($classe == 'Dashboard') {
        $classe = anchor($CI->router->class, 'Home');
    } else {
        $classe = anchor($CI->router->class, $classe);
    }
    $metodo = ucwords(str_replace('_', ' ', $CI->router->method));
    if ($metodo && $metodo != 'Index') {
        $metodo = " / ".anchor($CI->router->class."/".$CI->router->method, $metodo);
    } else {
        $metodo = '';
    }
    
    return '<p class="breadcrumbs">'.anchor('dashboard', 'Home').' / '.$classe.$metodo.'</p>';
    
    
}

// Insere um registro na tabela de auditoria
function auditoria($operacao, $obs, $query = TRUE){
    $CI = & get_instance();
    $CI->load->library('session');
    $CI->load->model('auditoria_model', 'auditoria');

      if ($query) {
        $last_query = $CI->db->last_query();
    } else {
        $last_query = '';
        }
    
        if (esta_logado(FALSE)) {
        $user_id = $CI->session->userdata('user_id');
        $user_login = $CI->usuarios->pegar_porId($user_id)->row()->login;
    } else {
        $user_login = 'Desconhecido';       
        }
        
        $dados = array(
            'usuario' => $user_login,
            'operacao' => $operacao,
            'query' => $last_query,
            'observacao' => $obs
        );

        $CI->auditoria->inserir_dados($dados);
}
