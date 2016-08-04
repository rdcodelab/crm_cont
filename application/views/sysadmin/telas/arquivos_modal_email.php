<script type="text/javascript">
$('#enviaMsg').modal('show');
</script>
<!-- Modal envia notificação-->
<div class="modal fade" id="enviaMsg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="<?=base_url('/admin/arquivos/envia_email'); ?>" method="post" enctype="multipart/form-data">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Enviar Notificação ao Cliente: <br /> <?=$cliente->razao_social; ?></h4>
      </div>
      <div class="modal-body">
          <div class="form-group">
              <label>Remetente</label>
              <input type="text" class="form-control" value="<?=$this->session->userdata('nome'); ?> (<?=$this->session->userdata('email'); ?>)" disabled />
          </div>
          <div class="form-group">
              <label>Destinatário</label>  <br />            
              <?php
                if(count($cliente_usuarios) == 0){
                    echo '<pre>Não há usuários cadastradas para este cliente.</pre>';
                }else{
                    foreach($cliente_usuarios as $us):
                        echo '<input type="checkbox" name="destinatarios[]" value="'.$us->email.'" /> '.$us->nome.' ('.$us->email.') <br />';
                    endforeach;
                }
              ?>
          </div>
          
          <div class="form-group">
              <label>Assunto:</label>
              <input type="text" name="assunto" class="form-control" value="Informações sobre o arquivo - <?=$arquivo->titulo; ?>" />
          </div>
          <div class="form-group">
              <label>Mensagem:</label>
              <textarea class="form-control" name="mensagem" placeholder="Texto da mensagem"></textarea>
          </div>          
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>        
        <button type="submit" class="btn btn-info">Enviar mensagem</button>
      </div>
    </form>
    </div>
  </div>
</div>