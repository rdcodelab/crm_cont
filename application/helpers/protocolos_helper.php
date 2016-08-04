<?php
/* 
 * Protocolos e Logs de ações executadas no sistema
 * Autor: Diego Sampaio - diego@estaleirodigital.com 
 */
// registra ação do usuário
function logs_acao($tipo_registro, $tipo_usuario, $cliente, $usuario, $setor, $arquivo, $acao){
    
    $CI = get_instance();
    $CI->load->model('Protocolos_model');
    
    $dados = array(
            'tipo_registro' => $tipo_registro,
            'tipo_usuario' => $tipo_usuario,
            'id_cliente' => $cliente,
            'id_usuario' => $usuario,
            'id_setor' => $setor,
            'id_arquivo' => $arquivo,
            'acao_protocolo' => $acao,
            'ip_acesso' => $_SERVER['SERVER_ADDR'],
            'session_id' => session_id()
        );
        
    $CI->Protocolos_model->add_registro($dados);     
    
}
/* 
 * Registro de notificações do sistema
 * Autor: Diego Sampaio - diego@estaleirodigital.com 
 */
// registra ação do usuário
function notificacoes($cliente, $usuario, $tipo, $setor, $titulo){
    
    $CI = get_instance();
    $CI->load->model('Protocolos_model');
    
    $dados = array(
            'id_cliente' => $cliente,
            'id_usuario' => $usuario,
            'tipo' => $tipo,
            'id_setor' => $setor,
            'titulo_notificacao' => $titulo,            
            'status' => 1
        );        
    $CI->Protocolos_model->add_notificacao($dados);         
}



