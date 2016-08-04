<?php
class Clientes_model extends CI_Model{
    
/******************************************************************************
 * CLIENTES
 *****************************************************************************/    
    public function lista_clientes(){
        $this->db->order_by('razao_social', 'asc');
        return $this->db->get('clientes')->result();
    }
    
    public function lista_cliente_id($id){ 
        $this->db->where('id_clientes', $id);
        return $this->db->get('clientes')->row(0);
    }
    
    public function add_clientes($dados = null, $usuarios = null, $servicos = null){
        if($dados != null):
            // cadastra cliente
            $this->db->insert('clientes', $dados);
            $id_cliente = $this->db->insert_id();
            
            // cadastra usuário do cliente
            if($usuarios != null):                
                $cliente = array('id_clientes' => $id_cliente);
                $lista = array_merge($cliente, $usuarios);
                $this->db->insert('clientes_usuarios', $lista);
                                
                $id_usuario = $this->db->insert_id();
                
                // adiciona permissão de arquivos ao usuário
                foreach($servicos as $serv):                    
                    $tipos_arquivos = $this->arquivos->lista_tipos_servico($serv);                                                            
                    foreach($tipos_arquivos as $arq):
                        $ln_tipo = array('idclientes_usuarios'=>$id_usuario, 'id_tipo'=>$arq->id_tipo);
                        $this->db->insert('clientes_usuarios_tiposarquivos', $ln_tipo);
                    endforeach;                                                                        
                endforeach;                                
            endif;
            
            // cadastra serviços
            if($servicos != null):
                foreach($servicos as $ln):
                    $lista = array('id_clientes' => $id_cliente, 'id_servicos' => $ln);
                    $this->db->insert('clientes_servicos', $lista);
                endforeach;
            endif;
            
            return true;
        endif;
    }
    
    // editar clientes
    public function editar_clientes($ln = array(), $servicos = array()){
        
        if(isset($ln['id_tipo'])):
            $this->db->set('id_tipo', $ln['id_tipo']);
        endif;
        
        if(isset($ln['cnpj'])):
            $this->db->set('cnpj', $ln['cnpj']);
        endif;
        
        if(isset($ln['insc_estadual'])):
            $this->db->set('insc_estadual', $ln['insc_estadual']);
        endif;
        
        if(isset($ln['insc_municipal'])):
            $this->db->set('insc_municipal', $ln['insc_municipal']);
        endif;
        
        if(isset($ln['razao_social'])):
            $this->db->set('razao_social', $ln['razao_social']);
        endif;
        
        if(isset($ln['nome_fantasia'])):
            $this->db->set('nome_fantasia', $ln['nome_fantasia']);
        endif;
        
        if(isset($ln['endereco'])):
            $this->db->set('endereco', $ln['endereco']);
        endif;
        
        if(isset($ln['bairro'])):
            $this->db->set('bairro', $ln['bairro']);
        endif;
        
        if(isset($ln['cep'])):
            $this->db->set('cep', $ln['cep']);
        endif;
        
        if(isset($ln['cidade'])):
            $this->db->set('cidade', $ln['cidade']);
        endif;
        
        if(isset($ln['estado'])):
            $this->db->set('estado', $ln['estado']);
        endif;
        
        if(isset($ln['responsavel'])):
            $this->db->set('responsavel', $ln['responsavel']);
        endif;
        
        if(isset($ln['email'])):
            $this->db->set('email', $ln['email']);
        endif;
        
        if(isset($ln['telefone'])):
            $this->db->set('telefone', $ln['telefone']);
        endif;
        
        if(isset($ln['celular'])):
            $this->db->set('celular', $ln['celular']);
        endif;
        
        if(isset($ln['celular_sms'])):
            $this->db->set('celular_sms', $ln['celular_sms']);
        endif;
        
        if(isset($ln['status'])):
            $this->db->set('status', $ln['status']);
        endif;
        
        $this->db->where('id_clientes', $ln['id_cliente']);
        $this->db->update('clientes');
        
        // altera serviços vinculados
        $this->db->where('id_clientes', $ln['id_cliente']);
        $this->db->delete('clientes_servicos');
        
        if(count($servicos) > 0):
            foreach ($servicos as $ls):
                $linha = array('id_clientes' => $ln['id_cliente'], 'id_servicos' => $ls);
                $this->db->insert('clientes_servicos', $linha);
            endforeach;
            
        endif;
        
        return $this->db->affected_rows();
    }
    
    public function excluir_cliente($id){
        // remove usuários do cliente
        $this->db->where('id_clientes', $id);
        $this->db->delete('clientes_usuarios');
        
        // deleta chamados
        $this->db->where('id_clientes', $id);
        $this->db->delete('chamados');
        
        // remove serviços de clientes
        $this->db->where('id_clientes', $id);
        $this->db->delete('clientes_servicos');
        
        // remove cliente
        $this->db->where('id_clientes', $id);
        return $this->db->delete('clientes');
        
    }
    
/******************************************************************************
 * USUÁRIOS CLIENTES
 *****************************************************************************/   
    // lista todos os usuários de clientes
    public function lista_todos_usuarios(){
        $this->db->select('idclientes_usuarios, nome');
        $this->db->where('status != ', 2);
        return $this->db->get('clientes_usuarios')->result();
    }
    
    // lista usuários de clientes por cliente
    public function lista_usuarios_cliente($cliente){
        $this->db->where('id_clientes', $cliente);
        $this->db->where('status != ', 2);
        return $this->db->get('clientes_usuarios')->result();
    }
    
    // lista usuários do cliente por id
    public function lista_cliente_usuario_id($id){
        $this->db->where('idclientes_usuarios', $id);
        $this->db->where('status != ', 2);
        return $this->db->get('clientes_usuarios')->row(0);
    }
    
    // lista todos os usuários vinculados a suas determinadas empresas
    public function lista_todosusuarios_cliente(){
        $this->db->from('clientes_usuarios cu');
        $this->db->join('clientes cl', 'cu.id_clientes = cl.id_clientes');
        $this->db->order_by('cu.nome, cl.razao_social', 'asc');
        return $this->db->get()->result();
        
    }
    
    // lista todos os usuários vinculados a suas determinadas empresas
    public function getUsuarioCliente($usuario){
        $this->db->from('clientes_usuarios cu');
        $this->db->join('clientes cl', 'cu.id_clientes = cl.id_clientes');
        $this->db->where('cu.idclientes_usuarios', $usuario);
        return $this->db->get()->row(0);
        
    }
    
    // valida usuario por email para recuperação de senha
    public function valida_usuario($email){
        $this->db->where('email', $email);
        return $this->db->get('clientes_usuarios')->row(0);
    }
    
    public function add_usuario($ln = null, $permissao = null){
        if($ln != null):
            $this->db->insert('clientes_usuarios', $ln);        
            $id_user = $this->db->insert_id();
            
            // associa permissão a pastas
            if($permissao != null):
                foreach($permissao as $ln2):
                    $lista = array('idclientes_usuarios' => $id_user, 'id_tipo' => $ln2);
                    $this->db->insert('clientes_usuarios_tiposarquivos', $lista);
                endforeach;
            endif;
        
            return true;
        
        endif;
    }
    
    public function editar_usuario($ln = array(), $permissao = null){
       
        if(isset($ln['nome'])):
            $this->db->set('nome', $ln['nome']);
        endif;
        if(isset($ln['foto'])):
            $this->db->set('foto', $ln['foto']);
        endif;
        if(isset($ln['email'])):
            $this->db->set('email', $ln['email']);
        endif;
        if(isset($ln['senha'])):
            $this->db->set('senha', $ln['senha']);
        endif;
        if(isset($ln['tipo'])):
            $this->db->set('tipo_usuario', $ln['tipo']);
        endif;
        if(isset($ln['status'])):
            $this->db->set('status', $ln['status']);
        endif;
        
        $this->db->where('idclientes_usuarios', $ln['id_usuario']);
        $this->db->update('clientes_usuarios');                
        
        // associa permissão a pastas
        if($permissao != null):            
            
            // remove todos os registros para atualização
            $this->db->where('idclientes_usuarios', $ln['id_usuario']);
            $this->db->delete('clientes_usuarios_tiposarquivos');

            
            foreach($permissao as $ln2):
                $lista = array('idclientes_usuarios' => $ln['id_usuario'], 'id_tipo' => $ln2);
                $this->db->insert('clientes_usuarios_tiposarquivos', $lista);
            endforeach;
        endif;
            
        return $this->db->affected_rows();
    }
    
    public function del_usuario($id){
        
        // remove todos os registros para atualização
        $this->db->where('idclientes_usuarios', $id);
        $this->db->delete('clientes_usuarios_tiposarquivos');        
        
        $this->db->where('idclientes_usuarios', $id);
        return $this->db->delete('clientes_usuarios');
    }
    
    // validação de dados de acesso
    public function login($dados = array()){
        $this->db->from('clientes cli');
        $this->db->select('clu.*, cli.*, cli.status AS cliente_status, clu.status AS user_status');
        $this->db->join('clientes_usuarios clu', 'cli.id_clientes = clu.id_clientes');
        $this->db->where('clu.email', $dados['usuario']);        
        $this->db->where('clu.senha', $dados['senha']);
        //$this->db->where('cli.status', 1);
        //$this->db->where('clu.status', 1);
        
        $query = $this->db->get();
        
        if($query->num_rows() == 1){
            return $query->row(0);
        }else{
            return false;
        }        
    }
    
/******************************************************************************
 * TIPOS DE CLIENTES
 *****************************************************************************/    
    public function lista_tipos(){
        $this->db->order_by('nome_tipo', 'asc');
        return $this->db->get('clientes_tipos')->result();
    }
    
    public function lista_tipo_id($id){
        $this->db->where('id_tipo', $id);
        return $this->db->get('clientes_tipos')->row();
    }
    
    public function add_tipo($dados = null){
        if($dados != null):
            return $this->db->insert('clientes_tipos', $dados);
        endif;
    }
    
    public function atualiza_tipo($dados = array()){
        if(isset($dados['nome_tipo'])):
            $this->db->set('nome_tipo', $dados['nome_tipo']);
        endif;
        
        $this->db->where('id_tipo', $dados['id_tipo']);
        $this->db->update('clientes_tipos');
        return $this->db->affected_rows();
    }
    
    // excluir tipo de clientes
    public function del_tipo($id){
        $this->db->where('id_tipo', $id);
        return $this->db->delete('clientes_tipos');
    }
    
/******************************************************************************
 * LISTA CLIENTES E SERVIÇOS
 *****************************************************************************/    
    public function lista_clientes_servicos(){
        return $this->db->get('clientes_servicos')->result();
    }
    
    // lista id dos servicos contratados pelo cliente
    public function lista_servicos_id($cliente){
        $this->db->where('id_clientes', $cliente);
        return $this->db->get('clientes_servicos')->result();
    }
    
    // lista serviços por clientes
    public function lista_servico_cliente($cliente){
        $this->db->from('servicos srv');
        $this->db->join('clientes_servicos cli', 'cli.id_servicos = srv.id');
        $this->db->where('cli.id_clientes', $cliente);
        $this->db->order_by('srv.nome', 'asc');
        $this->db->group_by('cli.id_servicos');
        return $this->db->get()->result();
    }
    
    // lista tipos de arquivos disponíveis para os usuários dos clientes
    public function lista_tipoArquivo_cliente($cliente){
        $this->db->select('cls.id_servicos, srv.nome, tpo.id_tipo, tpo.nome_tipo');
        $this->db->from('clientes_servicos cls');
        $this->db->join('servicos srv', 'srv.id = cls.id_servicos');
        $this->db->join('arquivos_tipos tpo', 'srv.id = tpo.id_servico');
        $this->db->where('cls.id_clientes', $cliente);
        $this->db->order_by('srv.nome', 'asc');
        $this->db->order_by('tpo.nome_tipo', 'asc');
        return $this->db->get()->result();
    }
    
    // lista tipos de arquivos vinculados ao usuário do cliente
    public function lista_tipoArquivo_usuario($usuario){
        $this->db->select('cliu.idclientes_usuarios, clitp.id_tipo, tpo.id_servico, tpo.nome_tipo, srv.nome');
        $this->db->from('clientes_usuarios cliu');
        $this->db->join('clientes_usuarios_tiposarquivos clitp', 'clitp.idclientes_usuarios = cliu.idclientes_usuarios');
        $this->db->join('arquivos_tipos tpo', 'tpo.id_tipo = clitp.id_tipo');
        $this->db->join('servicos srv', 'srv.id = tpo.id_servico');
        $this->db->where('cliu.idclientes_usuarios', $usuario);
        $this->db->order_by('srv.nome', 'asc');
        $this->db->order_by('tpo.nome_tipo', 'asc');
        return $this->db->get()->result();
        
    }
}