<?php
class Servicos_Model extends CI_Model{
    
    public function lista_servicos(){
        $this->db->order_by('nome', 'asc');
        return $this->db->get('servicos')->result();
    }
    
    // lista servicos em abas e setores
    public function getServicosDemandas($servico){
        // servico == 0 - Lista tudo        
        if($servico == 0){
            $this->db->order_by('nome', 'asc');           
        }else{
            $this->db->where('id', $servico);
        //    return $this->db->get('servicos')->row(0);
        } 
        return $this->db->get('servicos')->result();
    }
    
    public function lista_servico_id($id){
        $this->db->where('id', $id);
        return $this->db->get('servicos')->row();
    }
    
    public function adicionar($ln = null){
        if($ln != null):
            return $this->db->insert('servicos', $ln);
        endif;
    }
    
    public function editar($ln = array()){
        if(isset($ln['nome'])):
            $this->db->set('nome', $ln['nome']);
        endif;
        if(isset($ln['descricao'])):
            $this->db->set('descricao', $ln['descricao']);
        endif;       
        if(isset($ln['status'])):
            $this->db->set('status', $ln['status']);
        endif;
        
        $this->db->where('id', $ln['servico']);
        $this->db->update('servicos');
        
        return $this->db->affected_rows();
    }
    
    public function excluir($id){
        $this->db->where('id', $id);
        return $this->db->delete('servicos');
    }        
    
}