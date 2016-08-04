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
        <h3><i class="fa fa-envelope-o"></i> Solicitaçao de suporte n. #<?=$dados->id_chamados; ?></h3>
       
        <div class="row mt mb">
            
                  <div class="col-md-12">
                      
                      <?php
                            if($this->session->flashdata('sucesso')){
                                echo '<div class="alert alert-success" style="margin: 20px">
                                            <button type="button" class="close" data-dismiss="alert">&times;</button>                                    
                                            '.$this->session->flashdata('sucesso').'
                                        </div>';
                            }
                            if($this->session->flashdata('error')){
                                echo '<div class="alert alert-danger" style="margin: 20px">
                                            <button type="button" class="close" data-dismiss="alert">&times;</button>                                    
                                            '.$this->session->flashdata('error').'
                                        </div>';
                            }

                      
                        if($dados->id_usuarios == null){
                            echo '<div class="alert alert-warning">Esta solicitação ainda não foi atendida, clique em "Assumir Solicitação" para começar o atendimento.</div>';
                        }elseif($dados->status_chamado == 2){
                            echo '<div class="alert alert-warning">Esta solicitação foi fechada, não é possível o envio de novas mensagens.</div>';
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
                                                <i class="fa fa-calendar"></i> <?=dataHora_BR($ln->data_cadastro); ?> - Enviado por <i class="fa fa-user"></i> <b><?=$n_emitente; ?></b>
                                            </div>

                                            <div class="task-content">
                                                <?=$ln->mensagem; ?>        
                                                
                                                <div class="pull-right hidden-phone">
                                                    <?=($ln->status_mensagem == 0) ? '<span class="badge bg-theme msg_nova">Nova</span>' : ''; ?>
                                                </div>
                                            </div>
                                        </li>            
                                        <?php endforeach; ?>
                                    </ul>
                                <?php } ?>
                              </div>
                              <div class=" add-task-row">       
                                  <a class="btn btn-default btn-sm" href="<?=base_url('/admin/chamados'); ?>" title="Voltar">Voltar</a>
                                  <?php
                                    // verifica se o chamado já esta sendo atendido por algum funcionário
                                    if($dados->id_usuarios == null && $dados->status_chamado == 0){
                                        echo '<a href="'.base_url('/admin/chamados/usuario_assume/'.$dados->id_chamados.'/'.$this->session->userdata('id')).'" class="btn btn-warning">Assumir solicitação</a>';
                                    }else{
                                        echo ' <a href="javascript:void()" title="Enviar Mensagem" data-target="#addMensagem" data-toggle="modal" class="btn btn-primary btn-sm">Enviar Mensagem</a> ';
                                        echo ' <a href="javascript:void()" title="Fechar chamado" data-target="#fechaChamado" data-toggle="modal" class="btn btn-warning btn-sm">Fechar solicitação</a> ';
                                        echo ($ln->referencia == 1) ? '<script type="text/javascript"> window.onload = function(){ atualiza_status('.$dados->id_chamados.'); } </script>' : '';
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
        <form action="<?=base_url('/admin/chamados/add_resposta'); ?>" method="post" enctype="multipart/form-data">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Enviar mensagem</h4>
      </div>
      <div class="modal-body">
          <div class="form-group">
              <label for="nome">Cliente</label>
              <input type="text" class="form-control" value="<?=$cliente->razao_social; ?>" disabled  />
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
        <input type="hidden" name="usuario" value="<?=$this->session->userdata('id'); ?>" />
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
        <form action="<?=base_url('/admin/chamados/fechar_chamado'); ?>" method="post" enctype="multipart/form-data">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Fechar solicitação</h4>
            </div>
            <div class="modal-body">
                <p>Atenção ao fechar esta solicitação, não será mais possível enviar mensagens ao cliente.</p>
                <p>Certifique-se que a solicitação do cliente foi atendida!</p>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
              <input type="hidden" name="chamado" value="<?=$dados->id_chamados; ?>" />
              <input type="hidden" name="usuario" value="<?=$this->session->userdata('id'); ?>" />
              <button type="submit" class="btn btn-warning">Fechar solicitação</button>        
            </div>
        </form>
    </div>
  </div>
</div>

<script type="text/javascript">
function atualiza_status(chamado){
    
   $.ajax({
       type: 'GET',
       url: '<?=base_url('admin/chamados/leitura_mensagem/'); ?>/'+chamado,
       success: function(){
           $('.msg_nova').fadeOut(7000);
       }
   }); 
}
</script>