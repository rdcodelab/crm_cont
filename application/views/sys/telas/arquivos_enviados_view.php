<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <h3><i class="fa fa-cloud-upload"></i> Documentos enviados</h3>
        <div class="row mt">
            <div class="col-lg-12">
                <p> Documentos enviados ao escritório.</p>
                <div class="margin10">
                    <a href="javascript:void()" title="Enviar Documento" class="btn btn-info" data-toggle="modal" data-target="#addArquivo"><i class="fa fa-upload"></i> Enviar documento</a>
                    <a href="<?=base_url('/arquivos/'); ?>" title="Ver documentos enviados" class="btn btn-default">Documentos recebidos</a>
                </div>       
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
                
                    if(count($arquivos_enviados) == 0){                       
                        echo '<div class="alert alert-warning">Sua empresa ainda não enviou nenhum arquivo ao escritório.</div>';
                    }else{
                ?>
                
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Setor</th>
                            <th>Documento</th>
                            <th>Enviado por</th>
                            <th>Data do Envio</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            foreach($arquivos_enviados as $ln): 
                                $tipo = $this->arquivos->lista_tipo_id($ln->id_tipo);
                                $servico = $this->servicos->lista_servico_id($ln->id_servicos);                                
                                $usuario = $this->clientes->lista_cliente_usuario_id($ln->id_usuario);
                        ?>
                        <tr>
                            <td><?=$servico->nome." > ".$tipo->nome_tipo; ?></td>
                            <td><?=$ln->arquivo; ?></td>
                            <td><?=$usuario->nome; ?></td>
                            <td><?=dataHora_BR($ln->data_cadastro); ?></td>
                            <td>
                                <a href="javascript:void()" title="Excluir arquivo" class="btn btn-danger btn-sm delArquivo" data-toggle="modal" data-nome="<?=$ln->arquivo; ?>" data-id="<?=$ln->idarquivos; ?>" data-target="#delArquivo" data-sts="<?=$ln->status; ?>"><i class="fa fa-trash-o"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                
                <?php } ?>
                
            </div>
        </div>
    </section>
</section>


<!-- Modal ADD ARQUIVO-->
<div class="modal fade" id="addArquivo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <form action="<?=base_url('/arquivos/add'); ?>" method="post" id="frmAddArquivo"  enctype="multipart/form-data">
        <div class="modal-content">  
        
        <div class="modal-header"> 
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Enviar Documento</h4>
        </div>
        <div class="modal-body">
            <div class="msg_return"></div>

                <div class="msg_return2"></div>                    

                <div class="form-group">
                    <label for="tipo">Tipo de documento</label>
                    <div class="tipo_servico">
                        <select name="servico" required class="form-control">
                            <option value="">Selecione o tipo de documento</option>
                            <?php
                                foreach($tipos as $tp):
                                    echo '<option value="'.$tp->id_tipo.'">'.$tp->nome_tipo.'</option>';
                                endforeach;
                            ?>
                        </select>
                    </div>                        
                </div>

                  <div class="form-group">
                      <label for="arquivo">Arquivo</label>
                      <input type="file" name="userfile" class="form-control" id="arquivo" value="<?=set_value('userfile'); ?>" required />                          
                      <input type="hidden" name="titulo" id="nome_arquivo" />
                  </div>                     
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>        
        <input type="submit" class="btn btn-primary" id="btnAddArquivo" value="Enviar" />
        <input type="hidden" name="referencia" value="2" />
        <input type="hidden" name="usuario" value="<?=$this->session->userdata('id'); ?>" />
        <input type="hidden" name="cliente" value="<?=$this->session->userdata('id_cliente'); ?>" />
      </div>    
    </div>
    </form>
  </div>
</div>

<!-- Modal Excluir Arquivos-->
<div class="modal fade" id="delArquivo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="<?=base_url('arquivos/excluir'); ?>" method="post" enctype="multipart/form-data">
      <div class="modal-header" style="background: #d9534f;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Exclusão de Documentos</h4>
      </div>
      <div class="modal-body">
          <p>Deseja realmente EXCLUIR PERMANENTEMENTE o documento <b class="nome_arquivo"></b> ou manda-lo para lixeira?</p>
          <p>Ao excluir este registro, automaticamente serão deletados todas as informações vinculadas a ele no sistema.</p>
          <p>Após a confirmação de exclusão permanente, esta ação não poderá ser desfeita.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <input type="hidden" name="id" id="id_arquivo" />
        <input type="hidden" name="arquivo" class="n_arquivo" />
        <button type="submit" class="btn btn-danger">Excluir permanentemente</button>        
      </div>
    </form>
    </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
    
    // nomeação do arquivo]
    $('#arquivo').on('change', function(){
        
        var n_arquivo = $(this).val().split("\\").pop();
        // remove a extensão do arquivo
        var nome_arquivo = n_arquivo.split(".");
        
        // escreve o nome do arquivo
        $('#nome_arquivo').val(n_arquivo);
    });
    
     // modal de exclusão de clientes
    $('.delArquivo').on('click', function(){
        var nome = $(this).attr('data-nome');
        var id = $(this).attr('data-id');
        var status = $(this).attr('data-sts');
        
        $('.nome_arquivo').html(nome);
        $('.n_arquivo').val(nome);
        $('#id_arquivo').val(id);
        /*
        if(status == 3){
            $('#arquivo_lixeira').css('display', 'none');
        }else{
            $('#arquivo_lixeira').attr('href', '<?=base_url('/admin/arquivos/lixeira/'); ?>/'+id).css('display', 'inline');
        } */       
        
    });
});    
</script>