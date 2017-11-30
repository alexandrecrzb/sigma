<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Midia_model extends CI_Model {

    public function inserir_dados($dados = NULL, $redir = FALSE){
        if ($dados != NULL) {
            $this->db->insert('midia', $dados);
            if ($this->db->affected_rows() > 0) {
                set_msg('msg_ok', 'Arquivo enviado com sucesso', 'sucesso');
            } else {
                set_msg('msg_erro', 'Erro ao enviar arquivo', 'erro');
            }
            if($redir){
                redirect(current_url());
            }
        }
    }

    public function do_upload($campo){
        $config['upload_path'] = './uploads/imagens';
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $this->load->library('upload', $config);
        if ($this->upload->do_upload($campo)) {
            return $this->upload->data();
        } else {
            return $this->upload->display_errors();
        }   
    }

        public function pega_tudo(){
        return $this->db->get('midia');
    }
}

/* End of file midia_model.php */
/* Location: ./application/models/midia_model.php */