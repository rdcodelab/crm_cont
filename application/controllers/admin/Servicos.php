<?php
class Servicos extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('Servicos_model', 'servicos');
        $this->load->model('Configuracoes_model', 'configuracoes');
        $this->load->model('Usuarios_model', 'usuarios');
        $this->load->model('Chamados_model', 'chamados');
        $this->load->model('Tarefas_model', 'tarefas');
        
        // controle de nível de acesso
        // controle de nível de acesso
        $this->load->helper('Permissoes');
        controle_acesso($this->session->userdata('id'), 'servicos');
        
    }
    
    public function index(){
        if($this->session->userdata('usuarioLogado') == false){            
            $this->load->view('sysadmin/telas/login_view');
        }else{    
            $cabecalho = array(
                'config' => $this->configuracoes->lista_configs(1),
                'mensagens' => $this->chamados->lista_chamados_usuario($this->session->userdata('id')),
                'tarefas' => $this->tarefas->getTarefasStatusResp($this->session->userdata('id'), 1)
            );
            
            $menu = array(
                'menu' => $this->usuarios->nivel_acesso($this->session->userdata('id'))
            );
            
            $dados = array(
                'servicos' => $this->servicos->lista_servicos()                
            );
            
            $this->load->view('sysadmin/inc/header_html');
            $this->load->view('sysadmin/inc/header', $cabecalho);
            $this->load->view('sysadmin/inc/sidebar', $menu);
            $this->load->view('sysadmin/telas/servicos_view', $dados);
            $this->load->view('sysadmin/inc/footer');
            $this->load->view('sysadmin/inc/footer_html');
        }
    }
    
    public function add(){
        
        $this->form_validation->set_rules('nome', 'NOME DO SERVIÇO', 'trim|required|max_length[80]|ucwords');
        
        if($this->form_validation->run() == TRUE){   
            
            $dados = array(
                'nome' => $this->input->post('nome'),
                'descricao' => $this->input->post('descricao'),
                'status' => $this->input->post('status')
            );
            
            if($this->servicos->adicionar($dados)){
                
                // registra logs
                logs_acao(7, 2, null, $this->session->userdata('id'), null, null, '<div class="text-success">Cadastrou o serviço '.$this->input->post('nome').'</div>');
                
                $this->session->set_flashdata('sucesso', 'Serviço cadastrado com sucesso!');
                redirect('/admin/servicos/');      
               
            }else{
                $this->session->set_flashdata('error', 'Erro ao cadastrar serviço, tente novamente!');
                redirect('/admin/servicos');                                               
            }
        }else{
            $this->session->set_flashdata('error', 'Erro ao cadastrar serviço, tente novamente!');
            redirect('/admin/servicos');                                               
        }
                
    }
    
    
    public function editar(){
        
        $dados = array(
            'servico' => $this->input->post('servico'),
            'nome' => $this->input->post('nome'),
            'descricao' => $this->input->post('descricao'),    
            'status' => $this->input->post('status')
        );
        
        if($this->servicos->editar($dados)){
            
            // registra logs
            logs_acao(7, 2, null, $this->session->userdata('id'), $this->input->post('servico'), null, '<div class="text-info">Atualizou o serviço '.$this->input->post('nome').'</div>');
            
            $this->session->set_flashdata('sucesso', 'Registro atualizado com sucesso!');
            redirect('/admin/servicos');
        }else{
            $this->session->set_flashdata('error', 'Erro ao atualizar registro, tente novamente.!');
            redirect('/admin/servicos');
        }
        
    }
    
    public function excluir(){
        
        $serv = $this->servicos->lista_servico_id($this->input->post('servico'));
        $nome = $serv->nome;
        
        if($this->servicos->excluir($this->input->post('servico'))){
            
            // registra logs
            logs_acao(7, 2, null, $this->session->userdata('id'), $this->input->post('servico'), null, '<div class="text-danger">Deletou o serviço '.$nome.'</div>');
            
            $this->session->set_flashdata('sucesso', 'Registro excluído com sucesso!');
            redirect('/admin/servicos');
        }else{
            $this->session->set_flashdata('error', 'Erro ao excluir registro, tente novamente.!');
            redirect('/admin/servicos');
        }
    }
    
}