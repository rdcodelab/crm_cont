<?php
class Tarefas_model extends CI_Model{
    // lista todas as tarefas com excessão das entregues
    // tarefas do responsável pela execução
    public function getTarefasResponsavel($responsavel){
        $this->db->where('usuarios_responsaveis', $responsavel);
        $this->db->where('status !=', 3);
        $this->db->order_by('data_inicio', 'asc');
        return $this->db->get('tarefas')->result();
    }
    
    // lista tarefas por status e pelo resonsável
    // $responsavel => responsavel pela execução da tarefa
    // $status => status da tarefa
    public function getTarefasStatusResp($responsavel, $status){
        $this->db->where('usuarios_responsaveis', $responsavel);
        $this->db->where('status', $status);
        $this->db->order_by('data_inicio', 'asc');
        return $this->db->get('tarefas')->result();
    }
    
    // lista todas as tarefas com excessão das entregues
    // tarefas do autor
    public function getTarefasAutor($autor){
        $this->db->where('usuarios_autor', $autor);
        $this->db->where('usuarios_responsaveis !=', $autor);
        $this->db->where('status !=', 3);
        $this->db->order_by('data_inicio', 'asc');
        return $this->db->get('tarefas')->result();
    }
    
    // lista tarefas por status e pelo resonsável
    // $responsavel => responsavel pela execução da tarefa
    // $status => status da tarefa
    public function getTarefasStatusAutor($autor, $status){
        $this->db->where('usuarios_autor', $autor);
        $this->db->where('usuarios_responsaveis !=', $autor);
        $this->db->where('status', $status);
        $this->db->order_by('data_inicio', 'asc');
        return $this->db->get('tarefas')->result();
    }
    
    // lista tarefas por setor
    public function getTarefasSetor($responsavel, $setor){
        $this->db->from('tarefas_categorias cat');
        $this->db->join('tarefas tar', 'cat.id_categoria = tar.tarefas_categorias_id_categoria');
        $this->db->where('cat.id_servico', $setor);
        $this->db->where('tar.usuarios_responsaveis', $responsavel);
        $this->db->where('tar.status !=', 3);
        $this->db->order_by('tar.data_inicio', 'asc');
        return $this->db->get()->result();
    }
    
    // lista tarefas por setor
    // $entregue => 1-mostra as tarefas entregues/ 0=não mostra
    public function getFiltraTarefa($ln = array(), $entregue){
        $this->db->from('tarefas_categorias cat');
        $this->db->join('tarefas tar', 'cat.id_categoria = tar.tarefas_categorias_id_categoria');
        // filtra por setor
        if(isset($ln['setor'])){
            $this->db->where('cat.id_servico', $ln['setor']);
        }
        // filtra por responsavel
        if(isset($ln['responsavel'])){
            $this->db->where('tar.usuarios_responsaveis', $ln['responsavel']);
        }
        // filtra por período inicial
        if(isset($ln['data_inicial'])){
            $this->db->where('tar.data_inicio >=', $ln['data_inicial']);
        }
        // filtra pelo período final
        if(isset($ln['data_final'])){
            $this->db->where('tar.data_inicio <=', $ln['data_final']);
        }
        // filtra pelo período final
        if(isset($ln['status'])){
            $this->db->where('tar.status', $ln['status']);
        }
        if($entregue == 0){
            $this->db->where('tar.status !=', 3);
        }        
        $this->db->order_by('tar.data_inicio', 'asc');
        return $this->db->get()->result();
    }
    
    // lista tarefas por status, setor e pelo resonsável
    // $responsavel => responsavel pela execução da tarefa
    // $status => status da tarefa
    public function getTarefasSetorStatus($responsavel, $setor = null, $status){
        $this->db->from('tarefas_categorias cat');
        $this->db->join('tarefas tar', 'cat.id_categoria = tar.tarefas_categorias_id_categoria');
        $this->db->where('cat.id_servico', $setor);
        $this->db->where('tar.usuarios_responsaveis', $responsavel);
        $this->db->where('tar.status', $status);
        $this->db->order_by('tar.data_inicio', 'asc');
        return $this->db->get()->result();
    }
    
    // lista resumo de tarefas na home
    public function getResumoTarefas($usuario, $dataInicial){
        $this->db->where('usuarios_responsaveis', $usuario);
        $this->db->where('data_inicio <=', $dataInicial);
        $this->db->where('status !=', 3);
        return $this->db->get('tarefas')->result();
    }
    
    // lista tarefas por clientes
    public function getTarefasClientes($cliente, $setor = null){
        $this->db->select('cat.nome, tar.*');
        $this->db->from('tarefas_categorias cat');
        $this->db->join('tarefas tar', 'tar.tarefas_categorias_id_categoria = cat.id_categoria');
        $this->db->where('tar.id_cliente', $cliente);
        if($setor != null){
            $this->db->where('cat.id_servico', $setor);
        }
        //$this->db->where('tar.status !=', 3);
        $this->db->order_by('data_inicio', 'asc');
        return $this->db->get()->result();        
    }
    
    // lista tarefas abertas por clientes aos setores
    public function getDemandaTarefaCliente($setor){
        $this->db->select('tar.*, cat.*');
        $this->db->from('tarefas tar');
        $this->db->join('tarefas_categorias cat', 'cat.id_categoria = tar.tarefas_categorias_id_categoria');
        if($setor != 0):
            $this->db->where('cat.id_servico', $setor); // somente tarefas novas
        endif;
        $this->db->where('tar.status', 0); // somente tarefas novas
        $this->db->where('tar.origem_tarefa', '1'); // somente tarefas abertas por clientes
        $this->db->where('tar.usuarios_responsaveis IS NULL');
        return $this->db->get()->result();
    }
    
    // lista tarefas abertas por clientes aos setores
    public function getDemandaTarefaUsuarioCliente($usuario){
        $this->db->select('tar.*, cat.*');
        $this->db->from('tarefas tar');
        $this->db->join('tarefas_categorias cat', 'cat.id_categoria = tar.tarefas_categorias_id_categoria');        
        $this->db->where('tar.usuarios_autor', $usuario); // somente tarefas novas
        $this->db->where('tar.status !=', 3); // somente tarefas novas
        $this->db->where('tar.status !=', 4); // somente tarefas novas
        $this->db->where('tar.origem_tarefa', '1'); // somente tarefas abertas por clientes
        //$this->db->where('tar.usuarios_responsaveis IS NULL');
        return $this->db->get()->result();
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
    
    //atualiza tarefa
    public function editar($dados = array()){
        if(isset($dados['responsavel'])):
            $this->db->set('usuarios_responsaveis', $dados['responsavel']);
        endif;
        
        if(isset($dados['status_tarefa'])):
            $this->db->set('status', $dados['status_tarefa']);
        endif;
        
        if(isset($dados['progresso'])):
            $this->db->set('progresso', $dados['progresso']);
        endif;
        
        if(isset($dados['data_validade'])):
            $this->db->set('data_validade', $dados['data_validade']);
        endif;
        
        // verifica se a tarefa foi finalizada
        if(isset($dados['status_tarefa']) && $dados['status_tarefa'] == 3):
            $this->db->set('data_finalizacao', date('Y-m-d H:i:s'));
        endif;
        
        $this->db->where('id_tarefa', $dados['id_tarefa']);
        $this->db->update('tarefas');
        return $this->db->affected_rows();
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
 * MENSAGENS DE TAREFAS
 ******************************************************************************/    
    public function lista_msg_tarefa($tarefa){
        $this->db->where('id_tarefa', $tarefa);
        $this->db->order_by('data_cadastro', 'asc');
        return $this->db->get('tarefas_mensagens')->result();
    }
    
    public function add_msg($dados = null){
        if($dados != null):
            return $this->db->insert('tarefas_mensagens', $dados);
        endif;
    }
/*******************************************************************************
 * LOGS DE TAREFAS
 ******************************************************************************/    
    public function lista_logs($tarefa){
        $this->db->where('id_tarefa', $tarefa);
        $this->db->order_by('data_log', 'desc');
        return $this->db->get('tarefas_logs')->result();
    }
    
    public function add_log_tarefa($ln = null){
        if($ln != null):
            return $this->db->insert('tarefas_logs', $ln);
        endif;
    }
/*******************************************************************************
 * TIPOS DE TAREFAS
 ******************************************************************************/    
    public function lista_tipos(){
        $this->db->order_by('id_servico', 'asc');
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
        
        if(isset($ln['id_servico'])):
            $this->db->set('id_servico', $ln['id_servico']);
        endif;
        
        $this->db->where('id_categoria', $ln['id_categoria']);
        $this->db->update('tarefas_categorias');
        return $this->db->affected_rows();
    }
    
    public function del_tipo($id){
        $this->db->where('id_categoria', $id);
        return $this->db->delete('tarefas_categorias');
    }
    
    public function getTipoUsuario($usuario){
        $this->db->from('usuarios us');        
        $this->db->join('tarefas_categorias cat', 'cat.id_servico = us.id_servico');
        $this->db->where('us.id', $usuario);
        $this->db->order_by('cat.nome', 'asc');
        return $this->db->get()->result();
    }
    
    // SELECIONA SOMENTE AS CATEGORIAS DOS SETORES VINCULADOS AO CLIENTE
    public function getTipoTarefaCliente($cliente){
        $this->db->select('cli.id_clientes, cls.id_servicos, srv.nome, tcat.id_categoria, tcat.nome as categoria');
        $this->db->from('clientes cli');
        $this->db->join('clientes_servicos cls', 'cli.id_clientes = cls.id_clientes');
        $this->db->join('servicos srv', 'srv.id = cls.id_servicos');
        $this->db->join('tarefas_categorias tcat', 'tcat.id_servico = srv.id');
        $this->db->where('cli.id_clientes', $cliente);
        $this->db->order_by('srv.nome', 'asc');
        $this->db->order_by('tcat.nome', 'asc');
        return $this->db->get()->result();
    }
        
    
    public function getTipoTarefaSetor($setor){
        $this->db->where('id_servico', $setor);
        $this->db->order_by('nome', 'asc');
        return $this->db->get('tarefas_categorias')->result();
    }
}