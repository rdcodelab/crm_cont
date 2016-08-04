<?php
class Formulario extends CI_Controller{
    

    public function __construct() {
        parent::__construct();
        $this->load->model('Clientes_model', 'clientes');
        $this->load->model('Configuracoes_model', 'configuracoes');
        $this->load->model('Arquivos_model', 'arquivos');        
        $this->load->model('Chamados_model', 'chamados');        
        // realiza controle de status de arquivos.
        $this->arquivos->controle_status();
        
        if($this->session->userdata('clienteLogado') == false){            
            redirect('/home/login');
        }
    }
    
    public function index(){
        if($this->session->userdata('clienteLogado') == false){            
            $this->load->view('sys/telas/login_view');
        }else{    
            $cabecalho = array(
                'config' => $this->configuracoes->lista_configs(1),
                'mensagens' => $this->chamados->lista_chamados_cliente_usuario($this->session->userdata('id_cliente'), $this->session->userdata('id'), 1)
            );            
            
            $dados = array(
                'usuarios' => $this->clientes->lista_usuarios_cliente($this->session->userdata('id_cliente')), 
                'tipo_arquivo' => $this->clientes->lista_tipoArquivo_cliente($this->session->userdata('id_cliente'))
            );
            
            $this->load->view('sys/inc/header_html');
            $this->load->view('sys/inc/header', $cabecalho);
            $this->load->view('sys/inc/sidebar');
            $this->load->view('sys/telas/formulario_view', $dados);
            $this->load->view('sys/inc/footer');
            $this->load->view('sys/inc/footer_html');
        }
    }
    
    public function addFormulario() {
        
    }
    
    
}