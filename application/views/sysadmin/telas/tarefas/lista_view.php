

<table class="table table-hover tarefas">
    <tbody>
        <?php
            if(count($tarefas) == 0){
                echo '<div class="alert alert-warning">Não há tarefas vinculadas.</div>';
            }else{
                foreach($tarefas as $trf):
                    
                    // verifica se a tarefa esta atrasada ou não
                    if($trf->data_validade < date('Y-m-d') && $trf->status != 3){
                        $valTarefa = '<span class="label label-danger">Tarefa atrasada</span>';
                        $classTarefa = 'class="text-danger"';                        
                    }else{
                        $valTarefa = '';
                        $classTarefa = '';                        
                    } 
                    
                    // pega clientes se houver
                    $cliente = '';
                    if($trf->id_cliente != 0):
                        foreach($clientes as $cli):
                            if($trf->id_cliente == $cli->id_clientes):
                                $cliente = $cli->razao_social;
                            endif;
                        endforeach;
                    endif;
                    
                    // pega categoria da tarefa                    
                    foreach($tipos as $tp):
                        if($tp->id_categoria == $trf->tarefas_categorias_id_categoria):
                           $categoria = $tp->nome; 
                        endif;
                    endforeach;
                    
                    // monta botão de status de acordo com o status da tarefa
                    // 0 = mostra botão de iniciar tarefa
                    // 1 = Mostra botão de pausar tarefa
                    // 2 = Mostra o botão de avaliando
                    // 3 = Mostra botão de entregue e desabilita demais
                    // 4 = Mostra botão de tarefa pausada
                    switch ($trf->status){
                        case 0:
                            $status_tarefa = '<div class="label label-default">Nova</div>';
                            $btnStatus = '<a href="javascript:void()" onclick="mudaBtnTarefa('.$trf->id_tarefa.', 1)" title="Trabalhar Tarefa" id="btnAcao" class="btn btn-info btn-sm"><i class="glyphicon glyphicon-play"></i> Íniciar</a>';
                            $msg_entrega = '';
                        break;
                        case 1:
                            $status_tarefa = '<div class="label label-info">Trabalhando</div>';
                            $btnStatus = '<a href="javascript:void()" onclick="mudaBtnTarefa('.$trf->id_tarefa.', 4)" title="Pausar Tarefa" id="btnAcao" class="btn btn-warning btn-sm"><i class="glyphicon glyphicon-pause"></i> Pausar</a>';
                            $msg_entrega = '';
                        break;
                        case 2:
                            $status_tarefa = '<div class="label label-primary">Avaliando</div>';
                            $btnStatus = '<a href="javascript:void()" onclick="mudaBtnTarefa('.$trf->id_tarefa.', 1)" title="Avaliando Tarefa" id="btnAcao" class="btn btn-info btn-sm"><i class="glyphicon glyphicon-unchecked"></i> Avaliando</a>';
                            $msg_entrega = '';
                        break;
                        case 3:
                            $status_tarefa = '<div class="label label-success">Entregue</div>';
                            $btnStatus = '<a href="javascript:void()" title="Tarefa entregue" id="btnAcao" class="btn btn-success" disabled><i class="glyphicon glyphicon-check btn-sm"></i> Entregue</a>';
                            // verifica se a tarefa foi entregue no prazo
                            if($trf->data_validade < $trf->data_finalizacao){
                                $msg_entrega = '<span class="label label-danger">Tarefa entregue fora do prazo em '.implode("/", array_reverse(explode("-", ($trf->data_finalizacao)))).'</span>';
                            }else{
                                $msg_entrega = '<span class="label label-success">Tarefa entregue no prazo em '.implode("/", array_reverse(explode("-", ($trf->data_finalizacao)))).'</span>';
                            }
                        break;
                        case 4:
                            $status_tarefa = '<div class="label label-warning">Pausada</div>';
                            $btnStatus = '<a href="javascript:void()" onclick="mudaBtnTarefa('.$trf->id_tarefa.', 1)" title="Re-iniciar tarefa" id="btnAcao" class="btn btn-info btn-sm"><i class="glyphicon glyphicon-play"></i> Trabalhar</a>';
                            $msg_entrega = '';
                        break;
                    }                                                            
        ?>
                    <tr <?=$classTarefa; ?>>                        
                        <td>
                            <h4><a href="javascript:void()" title="Abrir tarefa" onclick="abre_tarefa(<?=$trf->id_tarefa; ?>)"><?=$trf->id_tarefa; ?> - <?=$trf->titulo; ?></a></h4>
                            <?=$cliente; ?> > <?=$categoria; ?><br />
                            <?=$msg_entrega; ?>
                        </td>
                        <td>
                            Começa em: <?=dataHora_BR($trf->data_inicio); ?><br />
                            Finaliza em: <?=dataHora_BR($trf->data_validade); ?><br />
                            
                        </td>
                        <td><?=$valTarefa; ?></td>                                                    
                            <?php
                                if($trf->usuarios_responsaveis == $this->session->userdata('id')){
                                   echo '<td><span class="statusBtn'.$trf->id_tarefa.'">'.$btnStatus.'</span></td>'; 
                                }                                
                            ?>                        
                    </tr>
        <?php 
                endforeach;
            } ?>
    </tbody>                                
</table>
                            