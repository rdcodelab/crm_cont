<?php
class Arquivos_Model extends CI_Model{
    
    // lista todos os arquivos
    public function lista_arquivos(){
        $this->db->where('referencia', '1');
        $this->db->where('status != ', '3');
        $this->db->order_by('data_cadastro', 'desc');
        return $this->db->get('arquivos')->result();               
    }
    
    // lista todos os arquivos
    public function lista_arquivos_limite_ordem($ref, $limite, $ordem){        
        $this->db->where('referencia', $ref);
        $this->db->where('status !=', '3');
        $this->db->order_by('data_cadastro', $ordem);
        $this->db->limit($limite);
        return $this->db->get('arquivos')->result();               
    }
    
    // lista arquivos enviados a um determinado cliente limitando exibições e ordenação de cadastro
    public function lista_arquivos_cliente_limite_ordem($cliente, $ordem){
        $this->db->where('id_clientes', $cliente);
        $this->db->where('referencia', 1);
        $this->db->where('status', 0);
        $this->db->order_by('data_cadastro', $ordem);
        return $this->db->get('arquivos')->result();
    }
    
    // lista arquivos recebidos de um determinado cliente limitando exibições e ordenação de cadastro
    public function lista_arq_recebidos_cliente_limite_ordem($cliente, $limite, $ordem){
        $this->db->where('id_clientes', $cliente);
        $this->db->where('referencia', 2);
        $this->db->order_by('data_cadastro', $ordem);
        return $this->db->get('arquivos')->result();
    }
    
    // lista arquivo por status
    public function lista_arquivos_status($sts, $ref){
        $this->db->where('referencia', $ref);
        $this->db->where('status', $sts);
        $this->db->order_by('data_cadastro', 'desc');
        return $this->db->get('arquivos')->result();
    }
    
   // lista arquivo por tipo e status
    public function lista_arquivos_tipo_status($tipo, $sts, $ref, $cliente = null){
        if($cliente != null){
            $this->db->where('id_clientes', $cliente);
        }
        $this->db->where('id_tipo', $tipo);
        $this->db->where('referencia', $ref);
        $this->db->where('status', $sts);
        $this->db->order_by('data_cadastro', 'desc');
        return $this->db->get('arquivos')->result();
    }
    
    // lista arquivos a vencer dentro de um período
    // data atual > data parametro
    public function lista_arquivos_a_vencer($d_expiracao){
        $this->db->where('data_vencimento <=', $d_expiracao);
        //$this->db->where('status !=', 2); // status de documento não aberto
        //$this->db->where('status !=', 3); // status de documento aberto
        $this->db->where('status', 0); // status de documento não aberto
        $this->db->order_by('data_vencimento', 'asc');
        return $this->db->get('arquivos')->result();
    }
    
    // lista arquivos com vencimentos do dia
    // $data = data de vencimento
    public function getArquivoVcto($cliente, $referencia, $data){
        $this->db->where('id_clientes', $data);
        $this->db->where('referencia', $referencia);
        $this->db->where('data_vencimento', $data);
        return $this->db->get('arquivos')->result();
    }
    
    // lista arquivos de um determinado cliente
    public function lista_arquivos_cliente($cliente){
        $this->db->where('id_clientes', $cliente);
        return $this->db->get('arquivos')->result();
    }
    
    // lista arquivo por id
    public function lista_arquivo_id($id){
        $this->db->where('idarquivos', $id);
        return $this->db->get('arquivos')->row(0);
    }
    
    // adiciona arquivo
    public function add_arquivos($ln = null){
        if($ln != null):
            return $this->db->insert('arquivos', $ln);
        endif;
    }
    
    // atualiza arquivos
    public function editar_arquivo($ln = array()){
        if(isset($ln['id_clientes'])):
            $this->db->set('id_clientes', $ln['id_clientes']);
        endif;
        
        if(isset($ln['id_servicos'])):
            $this->db->set('id_servicos', $ln['id_servicos']);
        endif;
        
        if(isset($ln['id_tipo'])):
            $this->db->set('id_tipo', $ln['id_tipo']);
        endif;
        
        if(isset($ln['titulo'])):
            $this->db->set('titulo', $ln['titulo']);
        endif;
        
        if(isset($ln['arquivo'])):
            $this->db->set('arquivo', $ln['arquivo']);
        endif;
        
        if(isset($ln['data_vencimento'])):
            $this->db->set('data_vencimento', $ln['data_vencimento']);
        endif;
        
        if(isset($ln['data_abertura'])):
            $this->db->set('data_abertura', $ln['data_abertura']);
        endif;
        
        if(isset($ln['status'])):
            $this->db->set('status', $ln['status']);
        endif;
        
        $this->db->where('idarquivos', $ln['id_arquivo']);
        $this->db->update('arquivos');
        
        return $this->db->affected_rows();
    }
    
    // controla status dos arquivos
    public function controle_status(){
        $this->db->set('status', '2');
        $this->db->where('data_vencimento <', date('Y-m-d'));
        $this->db->where('referencia', 1);
        $this->db->where('status !=', 3);
        $this->db->update('arquivos');
        return $this->db->affected_rows();
    }
    
    // verifica se o documento irá ser deletado do servidor
    public function lista_arquivos_exclusao(){
        $this->db->where('data_exclusao <', date('Y-m-d'));        
        return $this->db->get('arquivos')->result(); 
    }
    
    // exclui arquivo
    public function excluir($id){
        $this->db->where('idarquivos', $id);
        return $this->db->delete('arquivos');
    }
    
    // lista arquivos por filtros
    public function getArquivosFiltros($ln = array()){
        if(isset($ln['cliente']) && !empty($ln['cliente'])):
            $this->db->where('id_clientes', $ln['cliente']);
        endif;
        if(!empty($ln['documento'])):
            $this->db->like('titulo', $ln['documento']);
        endif;
        if(!empty($ln['setor'])):
            $this->db->where('id_tipo', $ln['setor']);
        endif;
        if($ln['status'] != null):
            $this->db->where('status', $ln['status']);
        endif;
        if(!empty($ln['envio_inicial'])):
            $this->db->where('data_cadastro >=', $ln['envio_inicial']);
        endif;
        if(!empty($ln['envio_final'])):
            $this->db->where('data_cadastro <=', $ln['envio_final']);
        endif;
        if(!empty($ln['validade_inicial'])):
            $this->db->where('data_vencimento >=', $ln['validade_inicial']);
        endif;
        if(!empty($ln['validade_final'])):
            $this->db->where('data_vencimento <=', $ln['validade_final']);
        endif;
        
        $this->db->order_by('data_cadastro', 'asc');
        return $this->db->get('arquivos')->result();
    }    
    

/*******************************************************************************
 * TIPOS DE ARQUIVOS
 ******************************************************************************/    
    public function lista_tipos(){
        $this->db->order_by('nome_tipo', 'asc');
        return $this->db->get('arquivos_tipos')->result();
    }
    // lista tipos de pastas por serviços com clientes    
    public function lista_tipos_servicos_clientes($cliente){
        $this->db->from('clientes_servicos cs');
        $this->db->join('arquivos_tipos ati','ati.id_servico = cs.id_servicos');
        $this->db->where('cs.id_clientes', $cliente);
        $this->db->order_by('ati.nome_tipo', 'asc');
        return $this->db->get()->result();
    }
    // lista tipos de arquivos por servico
    public function lista_tipos_servico($servico){
        if($servico != 0):
            $this->db->where('id_servico', $servico);
        endif;        
        $this->db->order_by('nome_tipo', 'asc');
        return $this->db->get('arquivos_tipos')->result();
    }
    
    public function lista_tipo_id($id){
        $this->db->where('id_tipo', $id);
        return $this->db->get('arquivos_tipos')->row();
    }
    
    public function add_tipo($ln = null){
        if($ln != null):
            return $this->db->insert('arquivos_tipos', $ln);
        endif;
    }
    
    public function atualiza_tipo($dados = array()){
        if(isset($dados['nome_tipo'])):
            $this->db->set('nome_tipo', $dados['nome_tipo']);
        endif;
        
        if(isset($dados['id_servico'])):
            $this->db->set('id_servico', $dados['id_servico']);
        endif;
        
        $this->db->where('id_tipo', $dados['id_tipo']);
        $this->db->update('arquivos_tipos');
        return $this->db->affected_rows();
    }
    
    // excluir tipo de clientes
    public function del_tipo($id){
        $this->db->where('id_tipo', $id);
        return $this->db->delete('arquivos_tipos');
    }
}