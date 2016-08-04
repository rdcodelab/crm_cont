<?php
// verifica qual a origem da tarefa
// 1 = Aberta pelo Cliente
// 2 = Aberta por um usuário
switch ($tarefa->origem_tarefa){
    case 1:
        $us = $this->clientes->lista_cliente_usuario_id($tarefa->usuarios_autor);
        $n_usuario = $us->nome;
    break;
    case 2:
        $us = $this->usuarios->lista_usuario_id($tarefa->usuarios_autor);
        $n_usuario = $us->nome;
    break;
}

// verifica quem é o usuário responsável pela tarefa
if($tarefa->usuarios_responsaveis == null){
    $responsavel = 'Solicitação não atendida';
}else{
    $resp = $this->usuarios->lista_usuario_id($tarefa->usuarios_responsaveis);
    $responsavel = $resp->nome;
}




// pega clientes se houver
$cliente = '';
if($tarefa->id_cliente != 0):
    foreach($clientes as $cli):
        if($tarefa->id_cliente == $cli->id_clientes):
            $cliente = $cli->razao_social;
        endif;
    endforeach;
endif;

// pega categoria da tarefa                    
$categoria = '';
foreach($tipos as $tp):
    if($tp->id_categoria == $tarefa->tarefas_categorias_id_categoria):
       $categoria = $tp->nome; 
    endif;
endforeach;

// monta botão de status de acordo com o status da tarefa
// 0 = mostra botão de iniciar tarefa
// 1 = Mostra botão de pausar tarefa
// 2 = Mostra o botão de avaliando
// 3 = Mostra botão de entregue e desabilita demais
// 4 = Mostra botão de tarefa pausada
switch ($tarefa->status){
    case 0:
        $btnStatus = '<a href="javascript:void()" onclick="mudaBtnTarefa('.$tarefa->id_tarefa.', 1)" title="Trabalhar Tarefa" id="btnAcao" class="btn btn-sm btn-info"><i class="glyphicon glyphicon-play"></i> Trabalhar</a>';
    break;
    case 1:
        $btnStatus = '<a href="javascript:void()" onclick="mudaBtnTarefa('.$tarefa->id_tarefa.', 4)" title="Pausar Tarefa" id="btnAcao" class="btn btn-sm btn-info"><i class="glyphicon glyphicon-pause"></i> Pausar</a>';
    break;
    case 2:
        $btnStatus = '<a href="javascript:void()" onclick="mudaBtnTarefa('.$tarefa->id_tarefa.', 1)" title="Avaliando Tarefa" id="btnAcao" class="btn btn-sm btn-info"><i class="glyphicon glyphicon-unchecked"></i> Avaliando</a>';
    break;
    case 3:
        $btnStatus = '<a href="javascript:void()" title="Tarefa entregue" id="btnAcao" class="btn btn-sm btn-success" disabled><i class="glyphicon glyphicon-check"></i> Entregue</a>';
    break;
    case 4:
        $btnStatus = '<a href="javascript:void()" onclick="mudaBtnTarefa('.$tarefa->id_tarefa.', 1)" title="Re-iniciar tarefa" id="btnAcao" class="btn btn-sm btn-info"><i class="glyphicon glyphicon-play"></i> Trabalhar</a>';
    break;
}
?>
<script type="text/javascript"> 
$(document).ready(function(){
    $('#modalTarefa').modal('show');
    carrega_mensagens(<?=$tarefa->id_tarefa; ?>);
});    
</script>
<!-- Modal -->
<div class="modal fade" id="modalTarefa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog tarefasModal" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title titulo_tarefa" id="myModalLabel">#<?=$tarefa->id_tarefa." - ".$tarefa->titulo; ?></h4>
      </div>
      <div class="modal-body">
          <div class="row">
              <div class="col-lg-12">
                  <div class="msg_retorno_modal"></div>
              </div>        
              
                <div class="text-right col-lg-12">  
                    <?php
                        if($tarefa->usuarios_responsaveis == null){
                            //echo anchor('/admin/tarefas/assumeSolicitacao/'.$tarefa->id_tarefa.'/'.$this->session->userdata('id'), 'Assumir Atendimento', 'class="btn btn-success"');
                            $atributos = array('class'=>'form-inline');
                            echo form_open('/admin/tarefas/assumeSolicitacao/', $atributos);
                            
                            echo '<div class="form-group margin10">';
                            echo '<label for="prazo">Previsão de entrega</label> ';
                            echo '<input type="text" class="form-control datepicker" name="dataEntrega" /> ';                            
                            echo '</div>';
                            echo '<div class="form-group margin10">';
                            echo ' <input type="submit" class="btn btn-success" value="Assumir Atendimento"  />';
                            echo '<input type="hidden" name="tarefa" value="'.$tarefa->id_tarefa.'" />';
                            echo '<input type="hidden" name="usuario" value="'.$this->session->userdata('id').'" />';
                            echo '</div>';
                            echo form_close();
                            
                            
                        }else{                            
                            echo '<span class="statusBtn'.$tarefa->id_tarefa.'">'.$btnStatus.'</span>';
                            if($tarefa->status != 3){
                                echo ' <a href="'.base_url('/admin/tarefas/status_tarefa/'.$tarefa->id_tarefa.'/3').'" title="Entregar Tarefa" id="btnAcao2" class="btn btn-sm btn-primary">Entregar</a>';
                            }
                        }                    
                    ?>                    
                </div>  
          </div>
          
          <div class="tarefa-box-info">
              <div class="row">
                  <div class="col-md-3"><b>Criado por</b></div>
                  <div class="col-md-9 responsavel_tarefa"><?=$n_usuario; ?></div>
              </div>
              <div class="row">
                  <div class="col-md-3"><b>Responsável</b></div>
                  <div class="col-md-9 responsavel_tarefa"><?=$responsavel; ?></div>
              </div>
              <div class="row">
                  <div class="col-md-3"><b>Tipo de Tarefa</b></div>
                  <div class="col-md-9 tipo_tarefa"><?=$categoria; ?></div>
              </div>
              <div class="row">
                  <div class="col-md-3"><b>Cliente</b></div>
                  <div class="col-md-9 cliente_tarefa"><?=$cliente; ?></div>
              </div>
              <div class="row">
                  <div class="col-md-3"><b>Ínicia em</b></div>
                  <div class="col-md-9 inicio_tarefa"><?=dataBR($tarefa->data_inicio); ?></div>
              </div>
              <div class="row">
                  <div class="col-md-3"><b>Entregar até</b></div>
                  <div class="col-md-9 fim_tarefa"><?=($tarefa->data_validade == '0000-00-00') ? 'N/d' : dataBR($tarefa->data_validade); ?></div>
              </div>
              <div class="row">
                  <div class="col-md-3"><b>Progresso</b></div>                  
                      <?php
                        if($tarefa->usuarios_responsaveis == null){
                            echo '<div class="col-md-9">Solicitação não atendida</div>';
                        }else{
                      ?>
                    <div class="col-md-8 fim_tarefa">  
                        <input type="range" class="barra-progresso" name="progresso" onchange="progresso_tarefa(<?=$tarefa->id_tarefa; ?>, this.value)" value="<?=$tarefa->progresso; ?>" />                                              
                    </div>
                  <div class="col-md-1 fim_tarefa progresso"><span class="label label-info"><?=$tarefa->progresso; ?>%</span></div>
                    <?php } ?>
              </div>
          </div><!--.tarefa-box-info-->
          
          <label><b>Descrição</b></label><br />
          <div class="descricao_tarefa">
            
            <div class="text-left col-md-12">
                <div class="descricao_tarefa tarefa-box-info">
                    <?=$tarefa->descricao; ?>
                </div>
            </div>                          
              
          </div>                    
          <div class="clearfix"></div>
           <?php
                if($tarefa->usuarios_responsaveis != null){
            ?>  
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                
                             
                <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                    <h4 class="panel-title" style="font-size: 12px">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#mensagens" aria-expanded="true" aria-controls="mensagens">
                        <i class="glyphicon glyphicon-envelope"></i> Mensagens
                    </a>
                  </h4>
                </div>
                <div id="mensagens" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">
                        <form id="frmMensagens<?=$tarefa->id_tarefa; ?>">
                            <div class="form-group">
                                <label for="msg">Mensagem</label>
                                <textarea name="mensagem" id="msg" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="usuario" value="<?=$this->session->userdata('id')?>" />
                                <input type="hidden" name="origem" value="2" />
                                <input type="hidden" name="tarefa" value="<?=$tarefa->id_tarefa; ?>" />
                                <a href="javascript:void()" title="Enviar Mensagem" id="btnMensagem" onclick="envia_mensagem(<?=$tarefa->id_tarefa; ?>)" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-envelope"></i> Enviar mensagem</a>
                            </div>
                        </form>
                                                
                        <div class="lista-msg"></div>
                    </div><!--.panel-body-->
                </div><!--#collapseOne-->
                </div><!--.panel-default-->
                
                
                
              <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                    <h4 class="panel-title" style="font-size: 12px">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <i class="glyphicon glyphicon-time"></i> Histórico
                    </a>
                  </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                  <div class="panel-body">
                      
                    <?php
                        if(count($logs) == 0){
                            echo '<pre>Esta tarefa não possui históricos até o momento.</pre>';
                        }else{
                            
                            foreach($logs as $ln):
                                
                                // verifica status
                                switch ($ln->status_tarefa){
                                    case 1:
                                        $stsTarefa = 'Íniciou a tarefa';
                                    break;
                                    case 4:
                                        $stsTarefa = 'Pausou a tarefa';
                                    break;
                                    case 3:
                                        $stsTarefa = 'Entregou a tarefa';
                                    break;
                                }
                                
                                // formata data do log
                                $data1   = substr($ln->data_log, 0, 10);
                                $data_br = implode("/", array_reverse(explode("-", $data1)));

                                // formata hora
                                $hora1 = substr($ln->data_log, 11, 8);
                                $dataLog = $data_br. ' '. $hora1;
                                
                                echo '<div class="col-md-12 tarefa-box-historico">';
                                echo '<i class="glyphicon glyphicon-calendar"></i> '.$stsTarefa.' em '.$dataLog;
                                echo '</div><!--.tarefa-box-historico-->';
                            endforeach;
                            
                        }
                        
                    ?>
                      
                        
                        
                  </div>
                </div>
            </div>
          
      </div><!--.modal-body-->
                <?php } ?>
      <div class="modal-footer">
          <div class="text-left col-md-4">
              <?php
                // só irá mostrar o botão de entregar se a tarefa ainda não tiver sido entregue               
                if($tarefa->status != 3 && $tarefa->usuarios_responsaveis != null){
                    echo '<a href="javascript:void()" title="Excluir Tarefa" onclick="excluir_tarefa('.$tarefa->id_tarefa.')" class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i></a>';
                    //echo '<a href="javascript:void()" title="Editar Tarefa" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-edit"></i></a>';
                }
              ?>
              
          </div>
                
      </div>
    </div>
  </div>
</div>   