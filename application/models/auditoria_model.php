<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auditoria_model extends CI_Model {

    public function inserir_dados($dados = NULL, $redir = FALSE){
        if ($dados != NULL) {
            $this->db->insert('auditoria', $dados);
            if ($this->db->affected_rows() > 0) {
                //set_msg('msg_ok', 'Usuário cadastrado com sucesso', 'sucesso');
            } else {
                set_msg('msg_erro', 'Erro ao cadastrar usuário', 'sucesso');
            }
            
            
            if($redir){
                redirect(current_url());
            }
        }
    }

    public function pegar_porId($id=NULL){
        if ($id != NULL) {
            $this->db->where('id', $id);
            $this->db->limit(1);
            return $this->db->get('auditoria');
        } else {
            return FALSE;
        }        
    }
    
    public function pega_tudo(){
        return $this->db->get('auditoria');
    }

    
}

/* End of file auditoria_model.php */
/* Location: ./application/models/auditoria_model.php */