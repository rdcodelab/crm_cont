<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <h3><i class="fa fa-cloud-upload"></i> Arquivos</h3>
        <div class="row mt">
            <div class="col-lg-12">
                <p> Arquivos cadastrados no sistema.</p>
                <div class="margin10">
                    <a href="javascript:void()" title="Enviar Documento" class="btn btn-info" data-toggle="modal" data-target="#addArquivo"><i class="fa fa-upload"></i> Enviar documento</a>
                    <a href="<?=base_url('/arquivos/docs_enviados'); ?>" title="Ver documentos enviados" class="btn btn-default">Documentos enviados</a>
                </div>
                <div class="retorno_msg"></div>
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
                    
                    if(count($tipos) == 0){
                        echo '<pre>Acesso restrito, entre em contato com o administrador de sua conta.</pre>';
                    }else{
                ?>                
                <div>
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <?php
                            $i = 0;
                            $tipo = "";
                            foreach($tipos as $tp):
                                if($i == 0){
                                    $tabAtiva = 'class="active"';
                                    $tipo = $tp->id_tipo;
                                }else{
                                    $tabAtiva = '';
                                }
                                echo '<li role="presentation" '.$tabAtiva.'><a href="#tipo-'.$tp->id_tipo.'" aria-controls="tipo-'.$tp->id_tipo.'" role="tab" data-toggle="tab" onclick="lista_tipo('.$tp->id_tipo.')">'.$tp->nome_tipo.'</a></li>';
                                $i++;
                            endforeach;
                        ?>                      
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="recebidos">
                            
                            <div class="alert">Lista de todos os arquivos e documentos recebidos do escritório.</div>
                            <div class="lista_arquivo"></div>

                        </div><!--#enviados-->                      
                    </div>                   
                </div> 
                <?php } ?>
                
            </div>
        </div>
    </section><!--/wrapper -->
</section><!--/MAIN CONTENT -->


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




<script src="<?=base_url('layout/admin/js/jquery.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){        
    
    lista_tipo(<?=$tipo; ?>);
    
    // modal de exclusão de clientes
    $('.delArquivo').on('click', function(){
        var nome = $(this).attr('data-nome');
        var id = $(this).attr('data-id');
        
        $('.nome_arquivo').html(nome);
        $('#id_arquivo').val(id);
        $('#arquivo_lixeira').attr('href', '<?=base_url('/arquivos/lixeira/'); ?>/'+id);
    });
    
    // nomeação do arquivo]
    $('#arquivo').on('change', function(){
        
        var n_arquivo = $(this).val().split("\\").pop();
        // remove a extensão do arquivo
        var nome_arquivo = n_arquivo.split(".");
        
        // escreve o nome do arquivo
        $('#nome_arquivo').val(n_arquivo);
    });
    
});

function protocolo_download(cliente, arquivo){
    $.ajax({
        type: 'POST',
        data: {'cliente':cliente, 'arquivo':arquivo},
        url: '<?=base_url('/arquivos/protocolo_download'); ?>',
        success: function(){
            location.reload();
        },
        error: function(){
            alert('Erro ao registrar protocolo');
        }
    });
}


// lista arquivos por tipo
function lista_tipo(tipo){
    $.ajax({
        type: 'POST',
        data: {'tipo':tipo},
        url: '<?=base_url('arquivos/lista_arquivos'); ?>',
        cache: false,
        beforeSend: function(){
            $('.lista_arquivo').html('<div style="text-align:center; padding: 10px;">Aguarde carregando arquivos... <br /><img src="<?=base_url('layout/admin/img/loader.GIF'); ?>" height="30" /></div>');
        },
        success: function(e){
            $('.lista_arquivo').html(e);
        },
        error: function(){
            $('.lista_arquivo').html('<div class="alert alert-danger">Erro ao carregar arquivos, atualize a pagina e tente novamente.</div>');
        }
    });
}
</script>