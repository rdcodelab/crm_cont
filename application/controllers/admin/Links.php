<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Links extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('Usuarios_model', 'usuarios');
        $this->load->model('Clientes_model', 'clientes');
        $this->load->model('Arquivos_model', 'arquivos');
        $this->load->model('Configuracoes_model', 'configuracoes');
        $this->load->model('Chamados_model', 'chamados');
        $this->load->model('Servicos_model', 'servicos');
        $this->load->model('Tarefas_model', 'tarefas');
        
        $this->load->helper('encode');
        
        /***********************************************************************
         * VERIFICA SE OS DOCUMENTOS SERÃO DELETADOS DO SERVIDOR E DO SISTEMA
         **********************************************************************/
        verifica_exclusao();  
        /***********************************************************************
         * DISPARA NOTIFICAÇÕES TODOS OS DIAS AS 10:00h
         **********************************************************************/
        if(date('H:i') == '10:00'):
            $dataExpiracao = SomarData(date('Y-m-d'), 5, 0, 0);
            arquivos_a_vencer($dataExpiracao);
        endif;                
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
                'menu' => $this->usuarios->nivel_acesso($this->session->userdata('id')),                
           );                   
            
           
            $this->load->view('sysadmin/inc/header_html');
            $this->load->view('sysadmin/inc/header', $cabecalho);
            $this->load->view('sysadmin/inc/sidebar', $menu);
            $this->load->view('sysadmin/telas/links/nfe_bel');
            $this->load->view('sysadmin/inc/footer');
            $this->load->view('sysadmin/inc/footer_html');
        }
    }
    // emissão de nfse
    public function nfse(){
        
        if($this->session->userdata('usuarioLogado') == false){
            
            $this->load->view('sysadmin/telas/login_view');
        }else{        
            
            $cabecalho = array(
                'config' => $this->configuracoes->lista_configs(1),
                'mensagens' => $this->chamados->lista_chamados_usuario($this->session->userdata('id')),
                'tarefas' => $this->tarefas->getTarefasStatusResp($this->session->userdata('id'), 1)
            );
            
            $menu = array(
                'menu' => $this->usuarios->nivel_acesso($this->session->userdata('id')),                
           );                   
            
           
            $this->load->view('sysadmin/inc/header_html');
            $this->load->view('sysadmin/inc/header', $cabecalho);
            $this->load->view('sysadmin/inc/sidebar', $menu);
            $this->load->view('sysadmin/telas/links/nfe_bel');
            $this->load->view('sysadmin/inc/footer');
            $this->load->view('sysadmin/inc/footer_html');
        }
    }
    
    // consulta CNPJ
    public function consulta_cnpj(){
        
        if($this->session->userdata('usuarioLogado') == false){
            
            $this->load->view('sysadmin/telas/login_view');
        }else{        
            
            $cabecalho = array(
                'config' => $this->configuracoes->lista_configs(1),
                'mensagens' => $this->chamados->lista_chamados_usuario($this->session->userdata('id')),
                'tarefas' => $this->tarefas->getTarefasStatusResp($this->session->userdata('id'), 1)
            );
            
            $menu = array(
                'menu' => $this->usuarios->nivel_acesso($this->session->userdata('id')),                
           );                   
            
           
            $this->load->view('sysadmin/inc/header_html');
            $this->load->view('sysadmin/inc/header', $cabecalho);
            $this->load->view('sysadmin/inc/sidebar', $menu);
            $this->load->view('sysadmin/telas/links/consulta_cnpj');
            $this->load->view('sysadmin/inc/footer');
            $this->load->view('sysadmin/inc/footer_html');
        }
    }
    
}