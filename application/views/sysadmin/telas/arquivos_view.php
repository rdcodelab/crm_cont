
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <h3><i class="fa fa-cloud-upload"></i> Expedição de Documentos</h3>
        <div class="row mt">
            <div class="col-lg-12">
                <p> Documentos cadastrados no sistema.</p>
                <p>                    
                    <a href="javascript:void()" title="Enviar Documento" class="btn btn-info" data-toggle="modal" data-target="#addArquivo">Enviar documento</a>
                    <?php
                        if($this->session->userdata('tipo') == 1){
                            echo '<a href="javascript:void()" title="Cadastrar Tipo de Documento" class="btn btn-default" data-toggle="modal" data-target="#addTipo">Tipos de Documentos</a>';
                        }
                    ?>
                </p>
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
                ?>
                <div>                    
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <?php
                            $i = 0;      
                            $tipo_atual = '';
                            $tab_ativo = '';                            
                            $tp = null;
                            foreach($tipos as $tp):
                               if($i === 0 && !isset($_GET['tp'])):
                                    $tab_ativo = 'class="active"';
                                    $tipo_atual = $tp->id_tipo;
                                elseif($_GET['tp'] == $tp->id_tipo):
                                    $tab_ativo = 'class="active"';
                                else:
                                    $tab_ativo = "";
                                endif;

                                //echo '<li role="presentation" '.$tab_ativo.'><a href="#tipo-'.$tp->id_tipo.'" aria-controls="tipo-'.$tp->id_tipo.'" role="tab" data-toggle="tab" onclick="lista_tipo('.$tp->id_tipo.')">'.$tp->nome_tipo.'</a></li>';
                                echo '<li role="presentation" '.$tab_ativo.'><a href="'.base_url('/admin/arquivos?tp='.$tp->id_tipo).'" aria-controls="tipo-'.$tp->id_tipo.'" role="_tab" data-toggle="_tab" onclick="lista_tipo('.$tp->id_tipo.')">'.$tp->nome_tipo.'</a></li>';
                                $i++;                         
                            endforeach;
                        ?> 
                    </ul>
                    
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <?php
                            $x = 0;                            
                            foreach($tipos as $tp2):
                                if($x == 0):
                                    $class_ativa = 'active';
                                else:
                                    $class_ativa = "";
                                endif;
                        ?>
                        <div role="tabpanel" class="tab-pane <?=$class_ativa; ?>" id="tipo-<?=$tp2->id_tipo; ?>">
                            <div class="lista_arquivo"></div><!--.lista-arquivo-->
                        </div><!--#tipo-<?=$tp2->id_tipo; ?>-->
                        <?php 
                            $x++;
                            endforeach; 
                        ?>
                        
                    </div>

                </div>
                                                
            </div>
        </div>
    </section><!--/wrapper -->
</section><!--/MAIN CONTENT -->


<!-- Modal ADD ARQUIVO-->
<div class="modal fade" id="addArquivo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <form action="<?=base_url('/admin/arquivos/add'); ?>" method="post" id="frmAddArquivo"  enctype="multipart/form-data">
        <div class="modal-content">  
        
            <div class="modal-header"> 
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Cadastrar Documento</h4>
            </div>
            <div class="modal-body">
                <div class="msg_return"></div>

                    <div class="msg_return2"></div>
                    <div class="form-group">
                        <label for="responsavel">Responsável pelo envio</label>
                        <input type="text" id="responsavel" class="form-control" value="<?=$this->session->userdata('nome'); ?>" disabled />
                        <input type="hidden" name="usuario" value="<?=$this->session->userdata('id'); ?>" />
                    </div>
                    <div class="form-group">
                        <label for="cliente">Cliente</label>
                        <select name="cliente" class="form-control" onchange="cliente_servico(this.value)" required>
                            <option value="">Selecione um cliente</option>
                            <?php
                              foreach($clientes as $cl):
                                  echo '<option value="'.$cl->id_clientes.'">'.$cl->razao_social.'</option>';
                              endforeach;
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="tipo">Tipo de documento</label>
                        <div class="tipo_servico">
                            <select name="servico" required class="form-control">
                                <option value="">Selecione um cliente primeiro</option> 
                            </select>
                        </div>                        
                    </div>
                    

                      <div class="form-group">
                          <label for="arquivo">Arquivo</label>
                          <input type="file" name="userfile" class="form-control" id="arquivo" value="<?=set_value('userfile'); ?>" required />                          
                          <input type="hidden" name="titulo" id="nome_arquivo" />
                      </div>

                      <div class="form-group">
                          <label for="vcto">Data Vencimento</label>
                          <input type="text" name="vcto" class="form-control datepicker" id="vcto" value="<?=set_value('vcto'); ?>" required />
                      </div>               

            </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>        
        <input type="submit" class="btn btn-primary" id="btnAddArquivo" value="Enviar" />
        <input type="hidden" name="referencia" value="1" />
      </div>    
    </div>
    </form>
  </div>
</div>



<!-- Modal Excluir Arquivos-->
<div class="modal fade" id="delArquivo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="<?=base_url('admin/arquivos/excluir'); ?>" method="post" enctype="multipart/form-data">
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
        <a id="arquivo_lixeira" class="btn btn-warning"><i class="glyphicon glyphicon-trash"></i> Lixeira</a>
      </div>
    </form>
    </div>
  </div>
</div>
<script src="<?=base_url('layout/admin/js/jquery.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){        
    
    $('#btnUpTipo').css('display', 'none');
    $('#id_tipo').css('display', 'none');
    $('#btnCancelarUp').css('display', 'none');
    $('#btnDelTipo').css('display', 'none');
    
    gera_tipo();    

    
    
    // adiciona cliente
    $('#btnAddTipo').on('click', function(){
        var dados = $('#frmTipo').serialize();        
        $.ajax({
            type: 'POST',
            data: dados,
            url: '<?=base_url('/admin/arquivos/addtipo'); ?>',
            beforeSend: function(){
                $('.msg_return').html('<div class="alert alert-warning">Cadastrando tipo, aguarde...</div>');
            },
            success: function(e){
                $('.msg_return').html(e);
                $('#tipo').val('');
                gera_tipo();
            },
            error: function(){
                $('.msg_return').html('<div class="alert alert-danger">Erro ao cadastrar tipo, tente novamente.</div>');
            }
            
        });
    });
    
    // atualização de tipos de clientes
    $('#btnUpTipo').on('click', function(){
        var dados = $('#frmTipo').serialize();
        
        $.ajax({
            type: 'POST',
            data: dados,
            url: '<?=base_url('/admin/arquivos/edita_tipo'); ?>',
            beforeSend: function(){
                $('.msg_return').html('<div class="alert alert-warning">Salvando dados, aguarde...</div>');
            },
            success: function(e){
                $('.msg_return').html(e).fadeOut(5000);
                $('#btnUpTipo').css('display', 'none');
                $('#id_tipo').css('display', 'none').val('');
                $('#btnAddTipo').css('display', 'inline');
                $('#btnCancelarUp').css('display', 'none');
                $('#tipo').val('');
                gera_tipo();
            },
            error: function(){
                $('.msg_return').html('<div class="alert alert-danger">Erro ao salvar dados, tente novamente.</div>').out(5000);
            }
        });
    });        
    // exclusão de tipos de clientes
    $('#btnDelTipo').on('click', function(){
        var dados = $('#frmTipo').serialize();
        
        $.ajax({
            type: 'POST',
            data: dados,
            url: '<?=base_url('/admin/arquivos/excluir_tipo'); ?>',
            beforeSend: function(){
                $('.msg_return').html('<div class="alert alert-warning">Salvando dados, aguarde...</div>');
            },
            success: function(e){
                $('.msg_return').html(e).fadeOut(5000);
                $('.msg_return2').html('');
                $('#btnUpTipo').css('display', 'none');
                $('#id_tipo').css('display', 'none').val('');
                $('#btnAddTipo').css('display', 'inline');
                $('#btnCancelarUp').css('display', 'none');
                $('#btnDelTipo').css('display', 'none');
                $('#tipo').val('');
                $('#tipo').removeAttr('disabled');
                gera_tipo();
            },
            error: function(){
                $('.msg_return').html('<div class="alert alert-danger">Erro ao salvar dados, tente novamente.</div>').out(5000);
            }
        });
    });        
    
    // modal de exclusão de clientes
    $('.delArquivo').on('click', function(){
        var nome = $(this).attr('data-nome');
        var id = $(this).attr('data-id');
        var status = $(this).attr('data-sts');
        
        $('.nome_arquivo').html(nome);
        $('#id_arquivo').val(id);
        if(status == 3){
            $('#arquivo_lixeira').css('display', 'none');
        }else{
            $('#arquivo_lixeira').attr('href', '<?=base_url('/admin/arquivos/lixeira/'); ?>/'+id).css('display', 'inline');
        }        
        
    });
    
    // cancela ação de update
    $('#btnCancelarUp').on('click', function(){
        $('#btnUpTipo').css('display', 'none');
        $('#id_tipo').css('display', 'none').val('');
        $('#btnDelTipo').css('display', 'none');
        $('#btnAddTipo').css('display', 'inline');
        $('#btnCancelarUp').css('display', 'none');
        $('#tipo').val('');
        $('#servico').val('');
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

function gera_tipo(){
    $.ajax({
        type: 'GET',
        url: '<?=base_url('/admin/arquivos/tipos_arquivos'); ?>',
        beforeSend: function(){
            $('#tipos_arquivos').html('<div class="alert alert-warning">Atualizando tabela, aguarde...</div>');
        },
        success: function(e){
            $('#tipos_arquivos').html(e);
        },
        error: function(){
            $('#tipos_arquivos').html('<div class="alert alert-danger">Erro ao atualizar tipos de arquivos, tente novamente.</div>');
        }
    });
}

function editar_tipo(id, tipo, servico){
    $('#btnUpTipo').css('display', 'inline');
    $('#id_tipo').css('display', 'block');
    $('#btnAddTipo').css('display', 'none');
    $('#btnCancelarUp').css('display', 'inline');
    $('#tipo').val(tipo).focus();
    $('#servico').val(servico);
    $('#id_tipo').val(id);
}

function excluir_tipo(id, tipo){
    $('#btnUpTipo').css('display', 'none');
    $('#btnDelTipo').css('display', 'inline');
    $('#id_tipo').css('display', 'block');
    $('#btnAddTipo').css('display', 'none');
    $('#btnCancelarUp').css('display', 'inline');
    $('#tipo').val(tipo).focus().attr('disabled', 'disabled');
    $('#id_tipo').val(id);
    $('.msg_return2').html('<div class="alert alert-danger">Deseja realmente excluir o registro abaixo? Após exclusão, esta ação não poderá ser desfeita! Caso haja algum registro de arquivos vinculados ao registro, a ação de exclusão não será possível.</div>');
}


function protocolo_download(cliente, arquivo){
    $.ajax({
        type: 'POST',
        data: {'cliente':cliente, 'arquivo':arquivo},
        url: '<?=base_url('/admin/arquivos/protocolo_download'); ?>',
        success: function(){
            location.reload();
        },
        error: function(){
            alert('Erro ao registrar protocolo');
        }
    });
}

<?php
    if(isset($_GET['tp']) && $_GET['tp'] != 0){
        echo 'lista_tipo('.$_GET['tp'].');';
    }else{
        echo 'lista_tipo('.$tipo_atual.');';
    }
?>

// lista arquivos por tipo
function lista_tipo(tipo){
    $.ajax({
        type: 'POST',
        data: {'tipo':tipo},
        url: '<?=base_url('admin/arquivos/lista_arquivos'); ?>',
        async: false,
        dataType: "html",
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
function cliente_servico(cliente){
    $.ajax({
        type: 'POST',
        data: {'cliente': cliente},
        url: '<?=base_url('/admin/arquivos/tipo_arquivo_cliente'); ?>',
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