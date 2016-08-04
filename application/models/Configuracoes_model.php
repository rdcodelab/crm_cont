<?php
class Configuracoes_Model extends CI_Model{
    
   public function lista_configs($id){
       $this->db->where('id', $id);
       return $this->db->get('configuracoes')->row(0);
   } 
   
   public function editar($dados = array()){
       
       if(isset($dados['logo'])):
           $this->db->set('logo_escritorio', $dados['logo']);
       endif;
       if(isset($dados['razao'])):
           $this->db->set('razao_social_escritorio', $dados['razao']);
       endif;
       if(isset($dados['fantasia'])):
           $this->db->set('nome_fantasia_escritorio', $dados['fantasia']);
       endif;
       if(isset($dados['cnpj'])):
           $this->db->set('cnpj_escritorio', $dados['cnpj']);
       endif;
       if(isset($dados['endereco'])):
           $this->db->set('endereco_escritorio', $dados['endereco']);
       endif;
       if(isset($dados['bairro'])):
           $this->db->set('bairro_escritorio', $dados['bairro']);
       endif;
       if(isset($dados['cep'])):
           $this->db->set('cep_escritorio', $dados['cep']);
       endif;
       if(isset($dados['telefone'])):
           $this->db->set('telefone_escritorio', $dados['telefone']);
       endif;
       if(isset($dados['email'])):
           $this->db->set('email_escritorio', $dados['email']);
       endif;
       if(isset($dados['site'])):
           $this->db->set('site_escritorio', $dados['site']);
       endif;
       if(isset($dados['vcto'])):
           $this->db->set('vcto_arquivos', $dados['vcto']);
       endif;
       
       $this->db->where('id', 1);
       $this->db->update('configuracoes');
       return $this->db->affected_rows();
   }
    
}