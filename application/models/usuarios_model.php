<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuarios_model extends CI_Model {

    public function inserir_dados($dados = NULL, $redir = TRUE){
        if ($dados != NULL) {
            $this->db->insert('usuarios', $dados);
            if ($this->db->affected_rows() > 0) {
                auditoria('Inclusão de usuario', 'Usuario cadastrado');
                set_msg('msg_ok', 'Usuário cadastrado com sucesso', 'sucesso');
            } else {
                set_msg('msg_erro', 'Erro ao cadastrar usuário', 'sucesso');
            }
            
            
            if($redir){
                redirect(current_url());
            }
        }
    }

    public function fazer_update($dados = NULL, $condicao = NULL, $redir = TRUE){
        if ($dados != NULL && is_array($condicao)) {
            $this->db->update('usuarios', $dados, $condicao);
            auditoria('Alteração de dados', 'Dados alterados');
            if ($this->db->affected_rows() > 0) {
               
                set_msg('msg_ok', 'Alteração efetuada com sucesso', 'sucesso');
            } else {
                set_msg('msg_erro', 'Erro ao atualizar registro', 'erro');
            }           
            
            if($redir){
                redirect(current_url());
            }
        }
    }

    public function deletar($condicao = NULL, $redir = TRUE){
        if ($condicao != NULL && is_array($condicao)) {
            auditoria('Exclusão de usuario', 'Usuario excluido');
            $this->db->delete('usuarios', $condicao);
            if ($this->db->affected_rows() > 0) {
                set_msg('msg_ok', 'Exclusão efetuada com sucesso', 'sucesso');
            } else {
               set_msg('msg_erro', 'Erro ao excluir registro', 'erro');
            }
            
            
             if($redir){
                redirect(current_url());
            }
        }
    }

    public function fazer_login($usuario = NULL, $senha = NULL){
        if ($usuario && $senha) {
            $this->db->where('login', $usuario);
            $this->db->where('senha', $senha);
            $this->db->where('ativo', 1);
            $query = $this->db->get('usuarios');
            if ($query->num_rows == 1) {
                return TRUE;
            } else {
                return FALSE;
            }
            
        } else {
            return FALSE;
        }        
    }

    public function pegar_porlogin($login=NULL){
        if ($login != NULL) {
            $this->db->where('login', $login);
            $this->db->limit(1);
            return $this->db->get('usuarios');
        } else {
            return FALSE;
        }        
    }

    public function pegar_porEmail($email=NULL){
        if ($email != NULL) {
            $this->db->where('email', $email);
            $this->db->limit(1);
            return $this->db->get('usuarios');
        } else {
            return FALSE;
        }        
    }
    public function pegar_porId($id=NULL){
        if ($id != NULL) {
            $this->db->where('id', $id);
            $this->db->limit(1);
            return $this->db->get('usuarios');
        } else {
            return FALSE;
        }        
    }
    
    public function pega_tudo(){
        return $this->db->get('usuarios');
    }

    
}

/* End of file usuarios_model.php */
/* Location: ./application/models/usuarios_model.php */