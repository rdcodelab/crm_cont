<?php
class Protocolos_model extends CI_Model{
    
    // lista todos os logs de um determinado cliente
    public function lista_logs_cliente($cliente, $d_inicial = null, $d_final = null){
        $this->db->from('clientes_usuarios cu');
        $this->db->join('protocolos pro', 'pro.id_usuario = cu.idclientes_usuarios');
        $this->db->where('pro.tipo_usuario', 1);
        $this->db->where('cu.id_clientes', $cliente);
        if($d_inicial != null):
            $this->db->where('pro.data_registro >=', $d_inicial); 
        endif;
        if($d_final != null):
            $this->db->where('pro.data_registro  <=', $d_final); 
        endif;
        
        $this->db->order_by('data_registro', 'desc');
        return $this->db->get()->result();        
    }
    
    // lista todos os logs
    public function lista_logs(){
        $this->db->where('tipo_usuario', 1);
        $this->db->order_by('data_registro', 'desc');
        return $this->db->get('protocolos')->result();
    }
    
    // lista logs com filtros de buscas
    public function lista_logs_filtros($tipo, $d_inicial, $d_final, $cliente = null, $usuario = null,  $sessao = null, $setores = null){            
        $this->db->from('protocolos prot');
        
        // filtra por clientes
        if($cliente != null && $cliente > 0 && $tipo == 1){
            $this->db->join('clientes_usuarios cliu', 'cliu.idclientes_usuarios = prot.id_usuario');
            $this->db->where('cliu.id_clientes', $cliente); 
        }        
        
        // filtra por usuário 
        if($usuario != null && $usuario > 0){
            $this->db->join('clientes_usuarios cliu', 'cliu.idclientes_usuarios = prot.id_usuario');
            $this->db->where('cliu.idclientes_usuarios', $usuario); 
        }        
        
        // filtra por sessão (Módulos)
        if($sessao != null && $sessao > 0){
            $this->db->where('prot.tipo_registro', $sessao);
        }
                
        // filtra por setor
        if($setores != null && !empty($setores)){
            $this->db->where('prot.id_setor', $setores);
        }
                
        $this->db->where('prot.data_registro >=', $d_inicial); 
        $this->db->where('prot.data_registro  <=', $d_final);
        
        if($tipo != null){
            $this->db->where('prot.tipo_usuario', $tipo);
        }        
        
        return $this->db->get()->result();
    }
    
    // lista todos os logs de um determinado usuário (gestor)
    public function lista_logs_usuarios($usuario){
        $this->db->where('tipo_usuario', 2);
        $this->db->where('id_usuario', $usuario);
        $this->db->order_by('data_registro', 'desc');
        return $this->db->get('protocolos')->result();
    }
    
    // adiciona registro
    public function add_registro($ln = null){
        if($ln != null):
            return $this->db->insert('protocolos', $ln);
        endif;
    }
/*******************************************************************************
 * NOTIFICAÇÕES
 ******************************************************************************/    
    public function add_notificacao($ln = null){
        if($ln != null):
            return $this->db->insert('notificacoes', $ln);
        endif;
    }
    
    public function atualiza_notificacao($ln = array()){
        if(isset($ln['status'])):
            $this->db->set('status', $ln['status']);
        endif;
        
        $this->db->where('id_notificacoes', $ln['id_notificacoes']);
        $this->db->update('notificacoes');
        return $this->db->affected_rows();
    }
    
    public function getNotificacaoSetor($setor){
        if($setor > 0):
            $this->db->where('id_setor', $setor);
        endif;
        $this->db->where('status', 1);
        $this->db->order_by('data_cadastro', 'desc');
        return $this->db->get('notificacoes')->result();
    }
    
    public function getIdNotificacao($id){
        $this->db->where('id_notificacoes', $id);
        return $this->db->get('notificacoes')->row(0);
    }
    
}