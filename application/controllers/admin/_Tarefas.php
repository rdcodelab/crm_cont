<?php
class Tarefas extends CI_Controller{
    
    public function __construct(){
        parent::__construct();     
                
        $this->load->model('Clientes_model', 'clientes');
        $this->load->model('Servicos_model', 'servicos');
        $this->load->model('Configuracoes_model', 'configuracoes');
        $this->load->model('Usuarios_model', 'usuarios');       
        $this->load->model('Chamados_model', 'chamados');       
        $this->load->model('Tarefas_model', 'tarefas');
        
        //$this->load->library('MY_Upload');
    }
    
    public function index(){
        
        if($this->session->userdata('usuarioLogado') == false){            
            $this->load->view('sysadmin/telas/login_view');
        }else{    
            $cabecalho = array(
                'config' => $this->configuracoes->lista_configs(1),
                'mensagens' => $this->chamados->lista_chamados_usuario($this->session->userdata('id'))
            );
            
            $menu = array(
                'menu' => $this->usuarios->nivel_acesso($this->session->userdata('id'))
            );
            
            $dados = array(
                'usuarios' => $this->usuarios->lista_usuarios(),
                'tipos' => $this->tarefas->lista_tipos(),
                'clientes' => $this->clientes->lista_clientes(),                
            );
                      
            
            $this->load->view('sysadmin/inc/header_html');
            $this->load->view('sysadmin/inc/header', $cabecalho);
            $this->load->view('sysadmin/inc/sidebar', $menu);
            $this->load->view('sysadmin/telas/tarefas_view', $dados); 
            $this->load->view('sysadmin/inc/footer');
            $this->load->view('sysadmin/inc/footer_html');
        }
        
    }
    
    public function lista(){
        $dados = array(
            'tarefas' => $this->tarefas->lista_tarefas_status($this->input->post('status')),
            'clientes' => $this->clientes->lista_clientes(),
            'tipos' => $this->tarefas->lista_tipos()
        );
        
        $this->load->view('sysadmin/telas/tarefas_lista_view', $dados);
    }
    
    // abre tarefa
    public function abre_tarefa(){
        $dados = array(
            'tarefa' => $this->tarefas->lista_tarefa_id($this->input->post('tarefa')),
            'clientes' => $this->clientes->lista_clientes(),
            'tipos' => $this->tarefas->lista_tipos(),
            'usuarios' => $this->usuarios->lista_usuarios(),
            'logs' => $this->tarefas->lista_logs($this->input->post('tarefa'))
        );
        
        $this->load->view('sysadmin/telas/tarefas_abertura_view', $dados);
    }
    
    public function add(){
        
        $this->form_validation->set_rules('titulo', 'TÍTULO DA TAREFA', 'trim|required');                

        if($this->form_validation->run() == TRUE){           
            
            $d_inicio = dataUS($this->input->post('data_inicial'));
            $d_final = dataUS($this->input->post('data_final'));
            $dados = array(
                'tarefas_categorias_id_categoria' => $this->input->post('tipo'),
                'usuarios_autor' => $this->session->userdata('id'),
                'usuarios_responsaveis' => $this->input->post('responsavel'),
                'id_cliente' => $this->input->post('cliente'),
                'titulo' => $this->input->post('titulo'),
                'descricao' => $this->input->post('descricao'), 
                'data_inicio' => $d_inicio,
                'data_validade' => $d_final,
                'status' => 0
            );
            
            if($this->tarefas->add_tarefa($dados)){
                $this->session->set_flashdata('sucesso', 'Tarefa criada com sucesso.');
                redirect('/admin/tarefas');
                
            }else{
                $this->session->set_flashdata('erro', 'Erro ao adicionar tarefa, tente novamente.');
                redirect('/admin/tarefas');
            }
            
        }else{            
            $this->session->set_flashdata('erro', 'Erro ao adicionar tarefa, tente novamente.');
            redirect('/admin/tarefas');
        }                
    }
    
    public function excluir_tarefa(){
        
        if($this->tarefas->excluir_tarefa($this->uri->segment(4))){
            echo '<div class="alert alert-success">Tarefa excluída com sucesso.</div>';
        }else{
            echo '<div class="alert alert-danger">Erro ao execluir tarefa, atualize sua página e tente novamente.</div>';
        }
        
    }
    
    
/*******************************************************************************
 * TIPOS DE CLIENTES
 ******************************************************************************/    
    public function tipos_tarefas(){
        $dados = array('tipos' => $this->tarefas->lista_tipos());
        
        $this->load->view('sysadmin/telas/tarefas_tipos_view', $dados);
    }
    
    public function addtipo(){
        $dados = array('nome'=>$this->input->post('tipo'));
        
        if($this->tarefas->add_tipo($dados)){
            
            // registra ação de logs
            logs_acao(3, 2, $this->session->userdata('id'), null, '<div class="text-success">Cadastrou o tipo de tarefa '.$this->input->post('tipo').'</div>');
            
            echo '<div class="alert alert-success">Tipo de tarefa cadastrado com sucesso.</div>';
        }else{
            echo '<div class="alert alert-danger">Erro ao cadastrar tipo de tarefa, tente novamente.</div>';
        }
    }
    
    public function edita_tipo(){
        
        $dados = array(
            'id_categoria' => $this->input->post('id_tipo'),
            'nome' => $this->input->post('tipo')
        );
        
        if($this->tarefas->atualiza_tipo($dados)){
            
            // registra ação de logs
            logs_acao(3, 2, $this->session->userdata('id'), null, '<div class="text-info">Atualizou o tipo de tarefa '.$this->input->post('tipo').'</div>');
            
            echo '<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Dados atualizados com sucesso.</div>';
        }else{
            echo '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Erro ao atualizar dados.</div>';
        }
        
    }
    
    
    public function excluir_tipo(){
        
        $tipo = $this->tarefas->lista_tipo_id($this->input->post('id_tipo'));
        $nome = $tipo->nome;
        
        if($this->tarefas->del_tipo($this->input->post('id_tipo'))){
            
            // registra ação de logs
            logs_acao(3, 2, $this->session->userdata('id'), null, '<div class="text-danger">Deletou o tipo de cliente '.$nome.'</div>');
            
            echo '<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Dados atualizados com sucesso.</div>';
        }else{
            echo '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Erro ao atualizar dados.</div>';
        }
    }    
    
    
}