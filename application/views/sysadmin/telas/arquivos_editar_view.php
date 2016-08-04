<?php
// resgata dados do usuário
foreach($usuarios as $us):
    if($arquivo->id_usuario == $us->id):
        $n_usuario = $us->nome;
    endif;
endforeach;

?>
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <h3><i class="fa fa-cloud-upload"></i> Arquivos</h3>
        <div class="row mt">
            <div class="col-lg-12">
                <p> Edição de Arquivo cadastrado no sistema.</p>
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
                ?>
                <form action="<?=base_url('admin/arquivos/editar/'.$arquivo->idarquivos); ?>" method="post">
                    <div class="msg_return"></div>

                        <div class="msg_return2"></div>
                        <div class="form-group">
                            <label for="responsavel">Responsável pelo envio</label>
                            <input type="text" id="responsavel" class="form-control" value="<?=$n_usuario; ?>" disabled />                            
                        </div>
                        <div class="form-group">
                            <label for="cliente">Cliente</label>
                            <select name="cliente" class="form-control" onchange="cliente_servico(this.value)" required>
                                <option value="">Selecione um cliente</option>
                                <?php
                                  foreach($clientes as $cl):
                                      if($arquivo->id_clientes == $cl->id_clientes){
                                          $selectCliente = 'selected';
                                      }else{
                                          $selectCliente = '';
                                      }
                                      echo '<option value="'.$cl->id_clientes.'" '.$selectCliente.'>'.$cl->razao_social.'</option>';
                                  endforeach;
                                ?>
                            </select>
                        </div>

                        <div class="form-group tipo_servico">
                            <?php echo $arquivo->id_servicos;
                                // se o arquivo estiver vinculado a um serviço do cliente o mesmo aparece aqui
                                if($arquivo->id_servicos != 0):
                                    echo '<label>Serviços</label>';
                                    echo '<select name="servico" class="form-control">';
                                        foreach($servicos as $srv):
                                            foreach($serv_clientes as $src):
                                                if($arquivo->id_clientes == $src->id_clientes && $src->id_servicos == $srv->id):
                                                    // seleciona tipo
                                                    if($arquivo->id_servicos == $srv->id){
                                                        $selectTipo = 'selected';
                                                    }else{
                                                        $selectTipo = '';
                                                    }
                                                    echo '<option value="'.$srv->id.'" '.$selectTipo.'>'.$srv->nome.'</option>';
                                                endif;                                                
                                            endforeach;                                            
                                        endforeach;
                                    echo '</select>';
                                endif;
                            ?>
                        </div>

                        <div class="form-group">
                            <label for="tipo">Tipo de arquivo</label>
                            <select name="tipo" class="form-control" required>
                                <option value="">Selecione um tipo de arquivo</option>
                                <?php
                                  foreach($tipos as $tp):
                                      
                                      if($tp->id_tipo == $arquivo->id_tipo){
                                          $selectTipo = 'selected';
                                      }else{
                                          $selectTipo = '';
                                      }
                                      
                                      echo '<option value="'.$tp->id_tipo.'" '.$selectTipo.'>'.$tp->nome_tipo.'</option>';
                                  endforeach;
                                ?>
                            </select>
                        </div>

                         <div class="form-group">
                            <label for="titulo">Título do arquivo</label>
                            <input type="text" name="titulo" class="form-control" id="titulo" value="<?=$arquivo->titulo; ?>" required />
                         </div>

                          <div class="form-group">
                              <label for="arquivo">Arquivo</label>
                              <?=anchor('/uploads/arquivos/'.$arquivo->arquivo, '<i class="glyphicon glyphicon-file"></i> Arquivo Atual', 'class="btn btn-info btn-sm" target="_blank"'); ?>                               
                              <a href="javascript:void()" title="Alterar Arquivo" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#formFile">Atualizar arquivo</a>
                          </div>

                          <div class="form-group">
                              <label for="vcto">Data Vencimento</label>
                              <input type="date" name="vcto" class="form-control" id="vcto" value="<?=$arquivo->data_vencimento; ?>" required />
                          </div>         
                                                
                        <div class="form-goup">
                            <a href="<?=base_url('/admin/arquivos/'); ?>" title="Voltar" class="label label-default">Voltar</a>
                            <input type="submit" class="btn btn-primary" value="Salvar dados" />
                            <input type="hidden" name="arquivo" value="<?=$arquivo->idarquivos; ?>" />
                            <small>Arquivo cadastrado em <?=dataHora_BR($arquivo->data_cadastro); ?> - exclusão do sistema em <?=dataBR($arquivo->data_exclusao); ?></smal>
                        </div>
                </form>
            </div>
        </div>
    </section>
</section>


<!-- Modal -->
<div class="modal fade" id="formFile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<?php echo form_open_multipart('admin/arquivos/atualizaFile'); ?>    
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Atualizar arquivo</h4>
      </div>
      <div class="modal-body">
        <?php
            echo '<span class="validacao"> '.  validation_errors().' </span>';
            
            echo form_fieldset();
            
            echo form_label("Novo arquivo", "userfile");
            echo '<input type="file" name="userfile" id="userfile" class="form-control" />';
            
            echo form_fieldset_close();                       
        ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>        
        <input type="submit" value="Salvar arquivo" class="btn btn-primary" />
        <input type="hidden" name="arquivoAntigo" value="<?=$arquivo->arquivo; ?>" />
        <input type="hidden" name="arquivo" value="<?php echo $arquivo->idarquivos; ?>" />
      </div>
    </div>
  </div>
<?php echo form_close();?>    
</div>




<script src="<?=base_url('layout/admin/js/jquery.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">

function cliente_servico(cliente){
    $.ajax({
        type: 'POST',
        data: {'cliente': cliente},
        url: '<?=base_url('/admin/arquivos/servico_cliente'); ?>',
        beforeSend: function(){
            $('.tipo_servico').html('<div class="alert alert-warning">Aguarde, carregando serviços vinculados...</div>');
        },
        success: function(info){
            $('.tipo_servico').html(info);
        },
        error: function(){
            $('.tipo_servico').html('<div class="alert alert-danger">Erro ao buscar serviços vinculados, tente novamente...</div>');
        }
    });
}
</script>