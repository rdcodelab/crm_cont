<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <h3><i class="fa fa-briefcase"></i> Clientes</h3>
        <div class="row mt">
            <div class="col-lg-12">
                <p> Clientes cadastrados no sistema.</p>
                <p>                    
                    <a href="<?=base_url('/admin/clientes/add'); ?>" title="Cadastrar Clientes" class="btn btn-info">Cadastrar cliente</a>
                    <a href="javascript:void()" title="Cadastrar Tipo de Cliente" class="btn btn-info" data-toggle="modal" data-target="#addTipo">Ramos de atividades</a>
                </p>
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
                    // verifica se há clientes cadastrados                    
                    if(count($clientes) == 0){
                        echo '<pre>Ainda não há clientes cadastrados no sistema.</pre>';
                    }else{
                ?>
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Razão Social</th>
                            <th>CNPJ</th>
                            <th>Responsável</th>
                            <th>Telefone</th>
                            <th>Serviços Contratados</th>
                            <th>Status</th>
                            <th>Opções</th>
                        </tr>
                    </thead>                    
                    <tbody>
                        <?php foreach($clientes as $ln): ?>
                        <tr>
                            <td><a href="<?=base_url('/admin/clientes/detalhes/'.$ln->id_clientes); ?>" title="Pasta do Cliente"><?=$ln->razao_social; ?></a></td>
                            <td><?=$ln->cnpj; ?></td>
                            <td><?=$ln->responsavel; ?></td>
                            <td><?=($ln->celular != "") ? $ln->telefone.' /'.$ln->celular : $ln->telefone; ?></td>
                            <td>
                                <?php
                                    foreach($servicos as $srv):
                                        foreach($clientes_servicos as $csr):
                                            if($srv->id == $csr->id_servicos && $csr->id_clientes == $ln->id_clientes):
                                                echo $srv->nome." <br />";
                                            endif;                                    
                                        endforeach;                                        
                                    endforeach;
                                ?>
                            </td>                            
                            <td><?=($ln->status == 1) ? '<span class="label label-success">Ativo</span>' : '<span class="label label-warning">Inativo</span>'; ?></td>                            
                            <td>
                                <a href="<?=base_url('/admin/clientes/detalhes/'.$ln->id_clientes); ?>" title="Pasta do Cliente" class="btn btn-default btn-sm infotooltip" data-toggle="tooltip" data-placement="top"><i class="fa fa-folder-open"></i></a>                                
                                <a href="<?=base_url('/admin/clientes/editar/'.$ln->id_clientes); ?>" title="Editar dados" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top"><i class="fa fa-edit"></i></a>                                
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                    <?php } ?>
            </div>
        </div>
    </section><!--/wrapper -->
</section><!--/MAIN CONTENT -->


<!-- Modal tipo de cliente-->
<div class="modal fade" id="addTipo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">        
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Ramos de atividades</h4>
      </div>
      <div class="modal-body">
          <div class="msg_return"></div>
          <form method="post" id="frmTipo">
              <div class="msg_return2"></div>
              <div class="form-group">
                  <label for="tipo">Ramo de atividade</label>
                  <input type="text" name="tipo" id="tipo" class="form-control" />
              </div>
              <div class="form-group">
                  <input type="hidden" name="id_tipo" id="id_tipo" />
                  <input type="button" class="btn btn-primary" id="btnAddTipo" value="Cadastrar">
                  <input type="button" class="btn btn-primary" id="btnUpTipo" value="Salvar">
                  <input type="button" class="btn btn-danger" id="btnCancelarUp" value="Cancelar">
                  <input type="button" class="btn btn-danger" id="btnDelTipo" value="Excluir permanentemente">
              </div>
          </form>
          <div id="tipos_clientes"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>        
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
            url: '<?=base_url('/admin/clientes/addtipo'); ?>',
            beforeSend: function(){
                $('.msg_return').html('<div class="alert alert-warning">Cadastrando tipo, aguarde...</div>');
            },
            success: function(e){
                $('.msg_return').html(e);
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
            url: '<?=base_url('/admin/clientes/edita_tipo'); ?>',
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
            url: '<?=base_url('/admin/clientes/excluir_tipo'); ?>',
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
        $('#tipo').removeAttr('disabled');
        $('.msg_return2').html('');
        $('.msg_return').html('');
    });
    
});

function gera_tipo(){
    $.ajax({
        type: 'GET',
        url: '<?=base_url('/admin/clientes/tipos_clientes'); ?>',
        beforeSend: function(){
            $('#tipos_clientes').html('<div class="alert alert-warning">Atualizando tabela, aguarde...</div>');
        },
        success: function(e){
            $('#tipos_clientes').html(e);
        },
        error: function(){
            $('#tipos_clientes').html('<div class="alert alert-danger">Erro ao atualizar tipos de clientes, tente novamente.</div>');
        }
    });
}

function editar_tipo(id, tipo){
    $('#btnUpTipo').css('display', 'inline');
    $('#id_tipo').css('display', 'block');
    $('#btnAddTipo').css('display', 'none');
    $('#btnCancelarUp').css('display', 'inline');
    $('#tipo').val(tipo).focus();
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
    $('.msg_return2').html('<div class="alert alert-danger">Deseja realmente excluir o registro abaixo? Após exclusão, esta ação não poderá ser desfeita! Caso haja algum registro de clientes vinculadas ao registro, a ação de exclusão não será possível.</div>');
}
</script>