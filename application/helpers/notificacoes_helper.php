<?php

function envia_email($destinatario, $assunto, $mensagem){
    $CI = get_instance();
    
    $CI->load->model('Configuracoes_model');
    $dados = $CI->Configuracoes_model->lista_configs(1);
    
    
    $CI->load->library('email');
    $CI->email->from('sistema@rdcode.com.br', "$dados->razao_social_escritorio");
    $CI->email->reply_to("$dados->email_escritorio", "$dados->razao_social_escritorio");
    $CI->email->to($destinatario);

    // configura mensagem no template
    $msg = template_notificacao($mensagem);
    
    $CI->email->subject($assunto);
    $CI->email->message($msg);

    if($CI->email->send()){
        return true;
    }else{
        return false;
    }        
    
}

// configuração de templates de envio de notificações
function template_notificacao($mensagem){
    // resgata dados da configuração do sistema
    $CI = get_instance();
    $CI->load->model('Configuracoes_model');
    $dados = $CI->Configuracoes_model->lista_configs(1);
    
    //@header('Content-Type: text/html; charset=utf-8');
    $template = "<style>";
    $template .= "*{ margin: 0; padding: 0; }";
    $template .= ".logo{ max-height: 100px; }";
    $template .= ".geral{ background: #6178a1; font-family: Arial, Tahoma, Serif; width: 100%; height: 100%; margin: 0; padding: 0; }";
    $template .= ".header-mensagem{ width: 90%; margin: 0 auto; padding: 10px; }";
    $template .= ".corpo-mensagem{ background: #6178a1; width: 90%; margin: 10px auto; padding: 10px; border-radius: 10px; box-shadow: 2px 2px 2px #333; min-height: 150px; }";
    $template .= ".rodape-mensagem{ text-align: center; font-size: 12px; background: #6178a1; color: #333; }";
    $template .= "</style>";
    $template .= '<div class="geral" style="background: #6178a1; font-family: Arial, Tahoma, Serif; width: 100%; height: 100%;">';
    $template .= '<div class="header-mensagem" style="width: 90%; margin: 0 auto; padding: 10px;">';
    $template .= '<img src="'.$dados->site_escritorio.'/uploads/imagens/'.$dados->logo_escritorio.'" title="'.$dados->razao_social_escritorio.'" class="logo" style="max-height: 100px;"/>';
    $template .= '</div><!--.header-mensagem -->';

    $template .= '<div class="corpo-mensagem" style="background: #f8f8f8; width: 90%; margin: 10px auto; padding: 10px; border-radius: 10px; box-shadow: 2px 2px 2px #333; min-height: 150px;">';
    $template .= $mensagem;
    $template .= '<br /><br /><small>Mensagem enviada em '.date('d/m/Y H:i:s').'</small>';
    $template .= '</div><!--.corpo-mensagem-->';

    $template .= '<div class="rodape-mensagem" style="text-align: center; font-size: 12px; background: #6178a1; color: #333;">';
    $template .= $dados->razao_social_escritorio.'<br />';    
    $template .= $dados->telefone_escritorio.' <br />';
    $template .= $dados->email_escritorio.' <br />';
    $template .= $dados->site_escritorio.' <br />';
    $template .= '</div><!--.corpo-mensagem-->';

    $template .= '</div><!--.geral-->';

    //@header('Content-Type: text/html; charset=utf-8');
    return $template;            
}

/*******************************************************************************
 * FAZ VERIFICAÇÃO E DISPARO DE ARQUIVOS A VENCER
 ******************************************************************************/
function arquivos_a_vencer($dataExpiracao){
    $CI = get_instance();
    $CI->load->model('Arquivos_model');
    $CI->load->model('Clientes_model');
    $CI->load->model('Usuarios_model');    
  
    /* @var $arquivos_vencer type */
    $arquivos_vencer = $CI->Arquivos_model->lista_arquivos_a_vencer($dataExpiracao);
    $clientes = $CI->Clientes_model->lista_clientes(); 
    $usuarios = $CI->Usuarios_model->lista_usuarios();
    $tipos = $CI->Arquivos_model->lista_tipos();
            
    if(count($arquivos_vencer) > 0){
        
        $msg  = 'Atenção! <br />';
        $msg  = 'Há '.count($arquivos_vencer).' arquivo(s) a vencer até o dia '.implode("/", array_reverse(explode("-", $dataExpiracao))).'<br />';
        $msg .= '<table style="width: 100%; border: 1px solid #ccc" border="1">';
        $msg .= '    <thead>';
        $msg .= '        <tr>';
        $msg .= '            <th>Arquivo</th>';
        $msg .= '            <th>Cliente</th>';                                                            
        $msg .= '            <th>Enviado por</th>';
        $msg .= '            <th>Cadastro Documento</th>';
        $msg .= '            <th>Vencimento Documento</th>';
        $msg .= '            <th>Status</th>';
        $msg .= '        </tr>';
        $msg .= '    </thead>';
        $msg .= '    <tbody>';

        foreach($arquivos_vencer as $ln): 
            /********************************************** 
             * formata datas
             *********************************************/ 
            $data_vencimento = implode("/", array_reverse(explode("-", $ln->data_vencimento)));
            // data e hora
            $data1   = substr($ln->data_cadastro, 0, 10);
            $data_br = implode("/", array_reverse(explode("-", $data1)));

            // formata hora
            $hora1 = substr($ln->data_cadastro, 11, 8);
            $data_cadastro = $data_br. ' '. $hora1;                
        
            /********************************************** 
             * pega dados cliente
             *********************************************/ 
            $nome_cliente = '';                                
            foreach($clientes as $cl):
                if($cl->id_clientes == $ln->id_clientes):
                    $nome_cliente = $cl->razao_social;                                        
                endif;                                
            endforeach;
            /********************************************** 
             * pega dados funcionário responsável
             *********************************************/ 
            $resp_arquivo = '';
            foreach($usuarios as $us):
                if($us->id == $ln->id_usuario):
                    $resp_arquivo = $us->nome;
                endif;
            endforeach;
                                                        
        $msg .= '<tr>';
        $msg .= '    <td>';
        $msg .=      anchor('uploads/arquivos/'.$ln->arquivo, $ln->titulo, 'target="_blank"');
                                                                
            foreach($tipos as $tp):
                if($tp->id_tipo === $ln->id_tipo):
                    $msg .= "<br /><small>".$tp->nome_tipo."</small>";
                endif;
            endforeach;                                                                
        $msg .= '   </td>';
        $msg .= '   <td>'.$nome_cliente.'</td>';
        $msg .= '   <td>'.$resp_arquivo.'</td>';
        $msg .= '   <td>'.$data_cadastro.'</td>';                            
        $msg .= '   <td>'.$data_vencimento.'</td>';
        $msg .= '   <td>';                          
            switch($ln->status){
                // arquivo pendente
                case 0 :
                    $msg .= '<span class="label label-default">Não aberto</span>';
                break;
                // arquivo visualizado
                case 1 :
                    $msg .= '<span class="label label-info">Visualizado</span>';
                break;
                // arquivo vencido
                case 2 :
                    $msg .= '<span class="label label-warning">Vencido</span>';
                break;
                // arquivo excluido (lixeira)
                case 3 :
                    $msg .= '<span class="label label-danger">Excluído</span>';
                break;
            }
        $msg .= '   </td>';                                                                                        
        $msg .= '</tr>';
        endforeach;
    $msg .= '</tbody>';
    $msg .= '</table>';
    $msg .= '<small>**Mensagem gerada automaticamente.</small>';
    
    
    foreach($usuarios as $us):
        envia_email($us->email, 'Arquivos a vencer até o dia '.implode("/", array_reverse(explode("-", $dataExpiracao))), $msg);
    endforeach;            
    
   }               
}
/*******************************************************************************
 * VERIFICA ARQUIVOS PARA EXCLUSÃO POR VENCIMENTO
 ******************************************************************************/   
function verifica_exclusao(){
     $CI = get_instance();
     $CI->load->model('Arquivos_model');

     $docs = $CI->Arquivos_model->lista_arquivos_exclusao();
     //var_dump($docs);
     if(count($docs) > 0):
         foreach($docs as $dc):
            $tipo = $CI->Arquivos_model->lista_tipo_id($dc->id_tipo);
            // registra log de exclusão automatica            
            logs_acao(1, 2, 0, $dc->id_servicos, $dc->idarquivos, '<div class="text-danger">Documento: <b>'.$tipo->nome_tipo.' - '.$dc->titulo.'</b> excluído automaticamente pelo sistema por estar fora de validade.</div>');                                         
            //echo base_url('/uploads/arquivos/'.$dc->arquivo);    
     
            $arquivo_del = './uploads/arquivos/'.$dc->arquivo;

            // deleta arquivo
            @unlink($arquivo_del);                
            $CI->Arquivos_model->excluir($dc->idarquivos);                 
            
         endforeach;
     endif;

}