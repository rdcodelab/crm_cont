<?php
class Chamados_model extends CI_Model{
    
    // lista todos os chamados
    public function lista_todos_chamados(){
        $this->db->order_by('data_cadastro', 'desc');
        return $this->db->get('chamados')->result();
    }
    
    // lista chamados por status
    public function lista_chamados_status($status = null, $servico = null){
        if($servico != 0):
            $this->db->where('id_servicos', $servico);
        endif;
        $this->db->where('status_chamado', $status);
        $this->db->order_by('data_cadastro', 'desc');
        return $this->db->get('chamados')->result();
    }
    
    // lista chamados por status
    public function lista_chamados_usuario_status($usuario, $status){
        $this->db->where('id_usuarios', $usuario);
        $this->db->where('status_chamado', $status);
        $this->db->order_by('data_cadastro', 'desc');
        return $this->db->get('chamados')->result();
    }
    
    // lista filtros dos chamados por filtros
    public function lista_filtro_chamados($ln = array()){
        if(!empty($ln['cliente'])):
            $this->db->where('id_clientes', $ln['cliente']);
        endif;
        if(!empty($ln['assunto'])):
            $this->db->like('assunto', $ln['assunto']);
        endif;
        if(!empty($ln['setor'])):
            $this->db->where('id_servicos', $ln['setor']);
        endif;
        if(!empty($ln['status']) && $ln['status'] >= '0'):
            $this->db->where('status_chamado', $ln['status']);
        endif;
        if(!empty($ln['envio_inicial'])):
            $this->db->where('data_cadastro >=', $ln['envio_inicial']);
        endif;
        if(!empty($ln['envio_final'])):
            $this->db->where('data_cadastro <x=', $ln['envio_final']);
        endif;                        
        if(!empty($ln['prioridade']) && $ln['prioridade'] >= 0):
            $this->db->where('nivel_urgencia', $ln['prioridade']);
        endif;                        
        
        $this->db->order_by('data_cadastro', 'desc');
        return $this->db->get('chamados')->result();
    }
    
    // lista chamados por usuário (atendimento)
    public function lista_chamados_usuario($usuario){
        $this->db->from('chamados cha');
        $this->db->join('chamados_mensagens chm', 'cha.id_chamados = chm.id_chamados');
        $this->db->join('clientes cli', 'cli.id_clientes = cha.id_clientes');
        $this->db->where('cha.id_usuarios', $usuario);
        $this->db->where('cha.status_chamado !=', 2);
        $this->db->where('chm.referencia', 1);
        $this->db->where('chm.status_mensagem', 0);
        $this->db->order_by('chm.data_cadastro', 'desc');
        $this->db->order_by('cha.data_cadastro', 'desc');        
        return $this->db->get()->result();
    }
    
    // lista dados chamado
    public function lista_dados_chamado($chamado){
        $this->db->where('id_chamados', $chamado);
        return $this->db->get('chamados')->row(0);
    }
    
    // lista mensagens do chamado
    public function lista_mensagens_chamado($chamado){
        $this->db->where('id_chamados', $chamado);
        $this->db->order_by('data_cadastro', 'asc');
        return $this->db->get('chamados_mensagens')->result();
    }
    
    // atualiza informações do chamado
    public function atualiza_chamado($ln = array()){
        if(isset($ln['id_usuarios'])):
            $this->db->set('id_usuarios', $ln['id_usuarios']);
        endif;
        
        if(isset($ln['status_chamado'])):
            $this->db->set('status_chamado', $ln['status_chamado']);                            
        endif;
        
        $this->db->where('id_chamados', $ln['id_chamados']);
        $this->db->update('chamados');
                
        // atualiza status das mensagens como LIDAS
        $this->db->set('status_mensagem', 1);                            
        $this->db->set('data_leitura', date('Y-m-d H:i:s'));                            
        $this->db->where('id_chamados', $ln['id_chamados']);
        $this->db->update('chamados_mensagens');
        
        return $this->db->affected_rows();
    }
        
    

/********************************************************************************
 * CHAMADOS CLIENTES
 *******************************************************************************/
    // lista todos os chamados de determinado cliente por usuário
    public function lista_chamado_cliente($cliente, $usuario){
        $this->db->where('id_clientes', $cliente);
        $this->db->where('idclientes_usuarios', $usuario);
        $this->db->order_by('data_cadastro desc');
        return $this->db->get('chamados')->result();
    }
    
    // lista todos os chamados de um determinado chamado
    public function lista_todoschamados_cliente($cliente, $limite){
        $this->db->where('id_clientes', $cliente);  
        $this->db->where('status_chamado !=', 2);
        $this->db->order_by('data_cadastro desc');
        $this->db->limit($limite);
        return $this->db->get('chamados')->result();
    }
    
    // lista chamados por usuário (atendimento)
    public function lista_chamados_cliente_usuario($cliente, $usuario){
        $this->db->from('chamados cha');
        $this->db->join('chamados_mensagens chm', 'cha.id_chamados = chm.id_chamados');
        $this->db->join('clientes cli', 'cli.id_clientes = cha.id_clientes');
        $this->db->where('cha.id_usuarios', $usuario);
        $this->db->where('cha.idclientes_usuarios', $cliente);
        $this->db->where('cha.status_chamado !=', 2);
        $this->db->where('chm.referencia', 2);
        $this->db->where('chm.status_mensagem', 0);
        $this->db->order_by('chm.data_cadastro', 'desc');
        $this->db->order_by('cha.data_cadastro', 'desc');        
        return $this->db->get()->result();
    }
    
    // cliente abre chamado
    public function add_chamado($dados = null, $msg = null){
        if($dados != null):
            $this->db->insert('chamados', $dados);
        
            $id = array('id_chamados' => $this->db->insert_id());
            // mescla os arrays
            $lista = array_merge($msg, $id);
            
            return $this->db->insert('chamados_mensagens', $lista);            
        endif;
    }
    
    // cliente enviando mensagem ao chamado
    public function add_mensagem($ln = null){
        if($ln != null):
            return $this->db->insert('chamados_mensagens', $ln);
        endif;
    }
    
    // atualiza leitura de mensagens
    public function atualiza_mensagens($chamado){
        $this->db->set('status_mensagem', 1);
        $this->db->set('data_leitura', date('Y-m-d H:i:s'));
        $this->db->where('id_chamados', $chamado);
        $this->db->update('chamados_mensagens');
        return $this->db->affected_rows();
    }

}