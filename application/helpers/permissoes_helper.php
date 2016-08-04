<?php
/* 
 * Controle de acesso aos módulos do sistema
 * Autor: Diego Sampaio - diego@estaleirodigital.com 
 */
// registra ação do usuário
function controle_acesso($usuario, $controller){
    
    $CI = get_instance();
    $CI->load->model('Usuarios_model');

    $dados = $CI->Usuarios_model->controle_acesso($usuario, $controller);
    
    if(count($dados) == 0){
        redirect('/admin/home/permissao_negada');
    }
}