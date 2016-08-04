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
$responsavel = $this->usuarios->lista_usuario_id($tarefa->usuarios_responsaveis);


// pega categoria da tarefa                    
$categoria = '';
foreach($tipos as $tp):
    if($tp->id_categoria == $tarefa->tarefas_categorias_id_categoria):
        // pega nome do setor
        $setor = $this->servicos->lista_servico_id($tp->id_servico);
       $categoria = $setor->nome.' > '.$tp->nome; 
    endif;
endforeach;

?>
<script type="text/javascript"> 
$(document).ready(function(){
    $('#modalTarefa').modal('show');
    carrega_mensagens(<?=$tarefa->id_tarefa; ?>);
});    
</script>
<!-- Modal -->
<div class="modal fade" id="modalTarefa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title titulo_tarefa" id="myModalLabel"><?=$tarefa->titulo; ?></h4>
      </div>
      <div class="modal-body">
          <div class="row">
              <div class="col-lg-12">
                  <div class="msg_retorno_modal"></div>
              </div>                                      
          </div>
          
          <div class="tarefa-box-info">
              <div class="row">
                  <div class="col-md-3"><b>Criado por</b></div>
                  <div class="col-md-9 responsavel_tarefa"><?=$n_usuario; ?></div>
              </div>
              <div class="row">
                  <div class="col-md-3"><b>Responsável</b></div>
                  <div class="col-md-9 responsavel_tarefa"><?=$responsavel->nome; ?></div>
              </div>
              <div class="row">
                  <div class="col-md-3"><b>Tipo de Tarefa</b></div>
                  <div class="col-md-9 tipo_tarefa"><?=$categoria; ?></div>
              </div>
              
              <div class="row">                  
                    <div class="col-md-3"><b>Ínicia em</b></div>
                    <div class="col-md-9 inicio_tarefa"><?=dataBR($tarefa->data_inicio); ?></div>                                          
              </div>
              
              <div class="row">                  
                    <div class="col-md-3"><b>Entregar até</b></div>
                    <div class="col-md-9 fim_tarefa"><?=dataBR($tarefa->data_validade); ?></div>                  
              </div>
              
              <div class="row">
                  <div class="col-md-3"><b>Status</b></div>
                  <div class="col-md-8 fim_tarefa">   
                    <?php
                      if($tarefa->status == 3){
                            $status = '<label class="label label-success">Realizado</label>';
                        }else{
                            $status = '<label class="label label-info">Em andamento</label>';
                        }
                        echo $status;
                    ?>
                  </div>                  
              </div>
              <div class="row">
                  <div class="col-md-3"><b>Progresso</b></div>
                  <div class="col-md-8 fim_tarefa">                                              
                      <div class="progress">
                          <div class="progress-bar" role="progressbar" aria-valuenow="<?=$tarefa->progresso; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$tarefa->progresso; ?>%;">
                          <?=$tarefa->progresso; ?>%
                        </div>
                      </div>
                  </div>                  
              </div>
          </div><!--.tarefa-box-info-->
          
          <label><b>Descrição</b></label><br />
          <div class="descricao_tarefa tarefa-box-info">
                        
                <?=$tarefa->descricao; ?>            
              
          </div>                    
          <div class="clearfix"></div>
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                
                <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                    <h4 class="panel-title" style="font-size: 12px">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#mensagens" aria-expanded="true" aria-controls="mensagens">
                        <i class="glyphicon glyphicon-envelope"></i> Enviar Mensagens
                    </a>
                  </h4>
                </div>
                <div id="mensagens" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">
                        <?php
                            // se a tarefa estiver fechada, não será possível enviar mensagens
                            if($tarefa->status == 3){
                                echo '<div class="alert alert-warning">Esta tarefa já foi finalizada, não é possível o envio de mensagens.</div>';
                            }else{
                        ?>
                        <form id="frmMensagens<?=$tarefa->id_tarefa; ?>">
                            <div class="form-group">
                                <label for="msg">Mensagem</label>
                                <textarea name="mensagem" id="msg" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="usuario" value="<?=$this->session->userdata('id')?>" />
                                <input type="hidden" name="origem" value="1" />
                                <input type="hidden" name="tarefa" value="<?=$tarefa->id_tarefa; ?>" />
                                <a href="javascript:void()" title="Enviar Mensagem" id="btnMensagem" onclick="envia_mensagem(<?=$tarefa->id_tarefa; ?>)" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-envelope"></i> Enviar mensagem</a>
                            </div>
                        </form>
                            <?php } ?>              
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
        
      <div class="modal-footer">
          <div class="text-left col-md-4">
              <?php
                // só irá mostrar o botão de entregar se a tarefa ainda não tiver sido entregue
                if($tarefa->status != 3 && $tarefa->status == 0){
                    echo '<a href="javascript:void()" title="Excluir Tarefa" onclick="excluir_tarefa('.$tarefa->id_tarefa.')" class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i></a>';
                    //echo '<a href="javascript:void()" title="Editar Tarefa" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-edit"></i></a>';
                }
              ?>
              
          </div>
                
      </div>
    </div>
  </div>
</div>