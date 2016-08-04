<?php
/*
 * Esta funcao apresenta a data e hora no formato brasileiro
 * * @access public
 * @param $dataBR
 */
function dataHora_BR($dataBR){
    // entrada da data yyyy-mm-dd      
    $data1   = substr($dataBR, 0, 10);
    $data_br = implode("/", array_reverse(explode("-", $data1)));

    // formata hora
    $hora1 = substr($dataBR, 11, 8);
    echo $data_br. ' '. $hora1;
}
/*
 * Esta função apresenta apenas data no formato brasileiro
 * @access public
 * @param $dataBR
 */
function dataBR($dataBR){
    // entrada da data yyyy-mm-dd
    $data_br = implode("/", array_reverse(explode("-", $dataBR)));
    
    echo $data_br;
}
/*
 * Esta função apresenta apenas data no formato americano yyyy-mm-dd
 * @access public
 * @param $dataBR
 */
function dataUS($dataBR){
    // entrada da data dd/mm/yyyy
    $data_us = implode("-", array_reverse(explode("/", $dataBR)));
    
    return $data_us;
}
/*
 * Esta função apresenta nome do mês por escrito de acordo com sua numeração
 * @access public
 * @param $mes
 */
function n_mes($mes){
    $meses = array('01'=> 'Jan', '02' => 'Fev', '03' => 'Mar', '04' => 'Mai', '06' => 'Jun', '07' => 'Jul', '08' => 'Ago', '09' => 'Set', '10' => 'Out', '11' => 'Nov', '12' => 'Dez');
    
    echo $meses[$mes];
}
/*
 * Esta função calcula uma data futura
 * @access public
 * @param $data, $dias, $meses, $ano
 */
function SomarData($data, $dias, $meses, $ano){
   /*www.brunogross.com*/
   //passe a data no formato Y-m-d
   $data = explode("-", $data);
   $newData = date("Y-m-d", mktime(0, 0, 0, $data[1] + $meses, $data[2] + $dias, $data[0] + $ano) );
   return $newData;
}
/*
 * Esta função calcula uma data passada
 * @access public
 * @param $data, $dias, $meses, $ano
 */
function SubData($data, $dias, $meses, $ano){
   /*www.brunogross.com*/
   //passe a data no formato Y-m-d
   $data = explode("-", $data);
   $newData = date("Y-m-d", mktime(0, 0, 0, $data[1] - $meses, $data[2] - $dias, $data[0] - $ano) );
   return $newData;
}

/*
 * Função que retorna status das tarefas
 * @access public
 * @param $sts
 */
function getStatus($sts){
    // formata status
    switch ($sts){
        case 0:
            $status_tarefa = '<div class="label label-default">Nova</div>';
        break;
        case 1:
            $status_tarefa = '<div class="label label-info">Trabalhando</div>';
        break;
        case 2:
            $status_tarefa = '<div class="label label-primary">Avaliando</div>';
        break;
        case 3:
            $status_tarefa = '<div class="label label-success">Entregue</div>';
        break;
        case 4:
            $status_tarefa = '<div class="label label-warning">Pausada</div>';
        break;
        default :
            $status_tarefa = '<div class="label label-danger">n/d</div>';
        break;
    
        return $status_tarefa;
    }    
}
/*
 * Função que retorna status dos Documentos
 * @access public
 * @param $sts
 */
function getStatusDoc($sts){
    // formata status
    switch ($sts){
        case 0:
            $status = '<div class="label label-default">Não aberto</div>';
        break;
        case 1:
            $status = '<div class="label label-info">Aberto</div>';
        break;
        case 2:
            $status = '<div class="label label-warning">Vencido</div>';
        break;
        case 3:
            $status = '<div class="label label-danger">Excluído</div>';
        break;     
        default :
            $status = '<div class="label label-default">n/d</div>';
        break;
    }
        echo $status;    
}
/*
 * Função que retorna status dos Chamados
 * @access public
 * @param $sts
 */
function getStatusChamados($sts){
    // formata status
    switch ($sts){
        case 0:
            $status = '<div class="label label-default">Novo</div>';
        break;
        case 1:
            $status = '<div class="label label-info">Pendente</div>';
        break;
        case 2:
            $status = '<div class="label label-warning">Fechado</div>';
        break;
        default :
            $status = '<div class="label label-default">n/d</div>';
        break;
    }
        echo $status;    
}
/*
 * Função que retorna nível de urgência
 * @access public
 * @param $sts
 */
function getNivelChamado($sts){
    // formata status
    switch ($sts){
        case 0:
            $status = '[BAIXA]';
        break;
        case 1:
            $status = '[NORMAL]';
        break;
        case 2:
            $status = '[ALTA]';
        break;        
        default :
            $status = '<div class="label label-default">n/d</div>';
        break;
    }
        echo $status;    
}
/*
 * Função que trata vetor de documentos nos multiplos uploads
 * @access public
 * @param $vetor
 */
function trata_vetorUpload($vetor){
    $novo_vetor = array();
    $file_count = count($vetor['name']);
    $file_keys = array_keys($vetor);
    
    for($i=0; $i<$file_count; $i++){
        foreach($file_keys as $key){
            $novo_vetor[$i][$key] = $vetor[$key][$i];
        }
    }
    
    return $novo_vetor;
}
/**
 * Apaga todos os arquivos temporários da pasta de arquivos
 * @param string $dir Caminho completo para o diretorio a esvaziar.
 */
function apagaTemporarios($dir) {

    if (is_dir($dir)) {

        $iterator = new \FilesystemIterator($dir);

        if ($iterator->valid()) {

            $di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
            $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);

            foreach ( $ri as $file ) {

                $file->isDir() ?  rmdir($file) : unlink($file);
            }
        }
    }
}