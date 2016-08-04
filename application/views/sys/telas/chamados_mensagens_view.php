 <?php
switch($dados->nivel_urgencia){
    case 0:
        $urgencia = '<span class="label label-default">Baixa</span>';
    break;
    case 1:
        $urgencia = '<span class="label label-info">Normal</span>';
    break;
    case 2:
        $urgencia = '<span class="label label-warning">Alta</span>';
    break;
}
?>
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <h3><i class="fa fa-envelope-o"></i> Solicitação de suporte via chamado n. #<?=$dados->id_chamados; ?></h3>
       
        <div class="row mt mb">
                  <div class="col-md-12">
                      <?php
                        if($dados->status_chamado == 2){
                            echo '<div class="alert alert-warning">Este chamado foi fechado, não é possível o envio de novas mensagens.</div>';
                        }
                      ?>
                      <section class="task-panel tasks-widget">
                            <div class="panel-heading">
	                        <div class="pull-left">
                                    <h5><i class="fa fa-envelope"></i> Assunto: <?=$dados->assunto; ?></h5>                                    
                                </div>
	                        <div class="pull-right">                                    
                                    <h5><i class="fa fa-calendar"></i> <?=dataHora_BR($dados->data_cadastro); ?>       <i class="fa fa-flag"></i> Prioridade: <?=$urgencia; ?></h5>
                                </div>
	                        <br>
                            </div>
                            <div class="panel-body">
                              <div class="task-content">
                                <?php
                                    if(count($mensagens) == 0){
                                        echo '<pre>Este chamado não possui mensagens.</pre>';
                                    }else{
                                ?>
                                    <ul id="sortable" class="task-list ui-sortable">
                                        <?php foreach($mensagens as $ln): ?>
                                        <li class="<?=($ln->status_mensagem == 0) ? 'list-primary' : 'list-unstyled'; ?>" style="<?=($ln->referencia == 2) ? 'margin-left: 50px' : '';?>">
                                            <i class=" fa fa-ellipsis-v"></i>
                                            
                                            <?php
                                                if($ln->referencia == 2){
                                                    $n_emitente = "";
                                                    foreach($lista_funcionario as $func):
                                                        if($ln->id_usuario == $func->id):
                                                            $n_emitente = $func->nome;
                                                        endif;
                                                    endforeach;
                                                }else{
                                                    $n_emitente = $usuario->nome;
                                                }                                                                                                
                                            ?>

                                            <div class="task-title-sp">
                                                <i class="fa fa-calendar-o"></i><?=dataHora_BR($ln->data_cadastro); ?> - Enviado por <b><i class="fa fa-user"></i> <?=$n_emitente; ?></b>                                                                                                                                                    
                                            </div>

                                            <div class="task-content">
                                                <?=$ln->mensagem; ?>        
                                                
                                                <div class="pull-right hidden-phone">
                                                    <?=($ln->status_mensagem == 0) ? '<span class="badge bg-theme msg_nova">Nova</span>' : ''; ?>
                                                </div>
                                            </div>
                                        </li>            
                                        <?php 
                                            endforeach; 
                                            // verifica status de leitura da última mensagem enviada pelo escritório
                                            echo ($ln->referencia == 2) ? '<script type="text/javascript"> window.onload = function(){ atualiza_status('.$dados->id_chamados.'); } </script>' : '';
                                        ?>
                                    </ul>
                                <?php } ?>
                              </div>
                              <div class=" add-task-row">    
                                  <a class="btn btn-default btn-sm" href="<?=base_url('/chamados'); ?>" title="Voltar">Voltar</a>
                                <?php 
                                    if($dados->status_chamado == 1){
                                        echo '<a href="javascript:void()" title="Enviar Mensagem" data-target="#addMensagem" data-toggle="modal" class="btn btn-primary btn-sm">Enviar Mensagem</a>';
                                        echo ' <a href="javascript:void()" title="Fechar chamado" data-target="#fechaChamado" data-toggle="modal" class="btn btn-warning btn-sm">Fechar chamado</a> ';
                                    }                                                                                
                                ?>                                                                                                    
                              </div>
                          </div>
                      </section>
                  </div><!--/col-md-12 -->
              </div><!-- /row -->
        
    </section><!--.wrapper-->
</section><!--#main-content-->
<!-- Modal -->
<div class="modal fade" id="addMensagem" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="<?=base_url('/chamados/add_mensagem'); ?>" method="post" enctype="multipart/form-data">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Enviar mensagem</h4>
      </div>
      <div class="modal-body">
          <div class="form-group">
              <label for="nome">Cliente</label>
              <input type="text" class="form-control" value="<?=$this->session->userdata('nome_cliente'); ?>" disabled  />
          </div>
          
          <div class="form-group">
              <label for="nome">Seu nome</label>
              <input type="text" class="form-control" value="<?=$this->session->userdata('nome'); ?>" disabled  />
          </div>
                    
          <div class="form-group">
              <label for="assunto">Assunto</label>
              <input type="text" class="form-control" name="assunto" placeholder="Assunto desejado" value="<?=$dados->assunto; ?>" disabled />
          </div>
          
          <div class="form-group">
              <label for="nome">Mensagem</label>
              <textarea class="form-control" name="mensagem" placeholder="Mensagem..."></textarea>
          </div>          
          
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <input type="hidden" name="chamado" value="<?=$dados->id_chamados; ?>" />
        <button type="submit" class="btn btn-primary">Cadastrar</button>        
      </div>
    </form>
    </div>
  </div>
</div>
<!-- Modal fecha chamado -->
<div class="modal fade" id="fechaChamado" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="<?=base_url('/chamados/fechar_chamado'); ?>" method="post" enctype="multipart/form-data">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Fechar chamado</h4>
            </div>
            <div class="modal-body">
                <p>Atenção ao fechar este chamado, não será mais possível enviar mensagens ao escritório.</p>
                <p>Caso queira poderá abrir um novo chamado para realizar novas solicitações.</p>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
              <input type="hidden" name="chamado" value="<?=$dados->id_chamados; ?>" />
              <input type="hidden" name="usuario" value="<?=$this->session->userdata('id'); ?>" />
              <button type="submit" class="btn btn-warning">Fechar chamado</button>        
            </div>
        </form>
    </div>
  </div>
</div>

<script type="text/javascript">  
    
function atualiza_status(chamado){

   $.ajax({
       type: 'GET',
       url: '<?=base_url('chamados/leitura_mensagem/'); ?>/'+chamado,
       success: function(){
           $('.msg_nova').fadeOut(7000);
       }
   }); 
}
</script>