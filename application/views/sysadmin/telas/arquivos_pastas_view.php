
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <h3><i class="fa fa-folder-open"></i> Pastas de Documentos</h3>
        <div class="row mt">
            <div class="col-lg-12">
                <p> Pastas de documentos, vinculadas aos setores.</p>


          <form method="post" id="frmTipo">
              <div class="msg_return2"></div>              
              <div class="form-group">
                  <label for="tipo">Tipo de documento</label> 
                  <input type="text" name="tipo" id="tipo" class="form-control" />
              </div>
              <div class="form-group">
                  <label for="servico">Serviço</label>
                  <select name="servico" id="servico" class="form-control">
                      <option value="">Selecione o serviço</option>
                      <?php
                        foreach($servicos as $serv):
                            echo '<option value="'.$serv->id.'">'.$serv->nome.'</option>';
                        endforeach;
                      ?>
                  </select>
              </div>
              <div class="form-group">
                  <input type="hidden" name="id_tipo" id="id_tipo" />
                  <input type="button" class="btn btn-primary" id="btnAddTipo" value="Cadastrar">
                  <input type="button" class="btn btn-primary" id="btnUpTipo" value="Salvar">
                  <input type="button" class="btn btn-danger" id="btnCancelarUp" value="Cancelar">
                  <input type="button" class="btn btn-danger" id="btnDelTipo" value="Excluir permanentemente">
              </div>
          </form>
          <div id="tipos_arquivos"></div>
      

    </div>
  </div>
    </section>
</section>
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
    
    // cancela ação de update
    $('#btnCancelarUp').on('click', function(){
        $('#btnUpTipo').css('display', 'none');
        $('#id_tipo').css('display', 'none').val('');
        $('#btnDelTipo').css('display', 'none');
        $('#btnAddTipo').css('display', 'inline');
        $('#btnCancelarUp').css('display', 'none');
        $('#tipo').val('');
        $('#servico').val('');
        $('.msg_return2').html('');
        $('#tipo').removeAttr('disabled');
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
</script>