<?php
class Tarefas_model extends CI_Model{
    
    public function lista_tarefas_status($status){
        $this->db->where('status', $status);
        $this->db->order_by('data_inicio', 'asc');
        return $this->db->get('tarefas')->result();
    }
    
    // lista tarefas por id
    public function lista_tarefa_id($id){
        $this->db->where('id_tarefa', $id);
        return $this->db->get('tarefas')->row(0);
    }
    
    // adiciona tarefas
    public function add_tarefa($ln = null){
        if($ln != null):
            return $this->db->insert('tarefas', $ln);
        endif;
    }
    
    // delete tarefas e suas ramificações
    public function excluir_tarefa($id){
        // exclui arquivos vinculados
        $this->db->where('id_tarefa', $id);
        $this->db->delete('tarefas_arquivos');
        
        // exclui logs de tarefas
        $this->db->where('id_tarefa', $id);
        $this->db->delete('tarefas_logs');
        
        // exclui tarefas
        $this->db->where('id_tarefa', $id);
        return $this->db->delete('tarefas');
        
    }
/*******************************************************************************
 * LOGS DE TAREFAS
 ******************************************************************************/    
    public function lista_logs($tarefa){
        $this->db->where('id_tarefa', $tarefa);
        $this->db->order_by('data_inicio', 'asc');
        return $this->db->get('tarefas_logs')->result();
    }
/*******************************************************************************
 * TIPOS DE TAREFAS
 ******************************************************************************/    
    public function lista_tipos(){
        $this->db->order_by('nome', 'asc');
        return $this->db->get('tarefas_categorias')->result();
    }
    
    public function lista_tipo_id($id){
        $this->db->where('id_categoria', $id);
        return $this->db->get('tarefas_categorias')->row(0);
    }
    
    public function add_tipo($ln = null){
        if($ln != null):
            return $this->db->insert('tarefas_categorias', $ln);
        endif;
    }
    
    public function atualiza_tipo($ln = array()){
        if(isset($ln['nome'])):
            $this->db->set('nome', $ln['nome']);
        endif;
        
        $this->db->where('id_categoria', $ln['id_categoria']);
        $this->db->update('tarefas_categorias');
        return $this->db->affected_rows();
    }
    
    public function del_tipo($id){
        $this->db->where('id_categoria', $id);
        return $this->db->delete('tarefas_categorias');
    }
}
