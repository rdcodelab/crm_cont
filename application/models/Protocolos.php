<?php
class Protocolos extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        
    }
    
    public function add_registro(){
        
        $dados = array(
            'tipo_registro' => $this->input->post('tipo_registro'),
            'tipo_usuario' => 2,
            'id_usuario' => $this->session->userdata('id'),
            'id_arquivo' => $this->input->post('arquivo'),
            'acao_protocolo' => $this->input->post('acao'),
            'ip_acesso' => $_SERVER['SERVER_ADDR'],
            'session_id' => session_id()
        );
        
        $this->protocolos->add_registro($dados);                
    }
    
    
}
