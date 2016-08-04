<div class="modal_tarefa"></div>
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <h3><i class="fa fa-list-ul"></i> Solicitações de Serviços</h3>
        <div class="row mt">
            <div class="col-lg-12">  
                
                <div>
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
                                        
                    <div class="tarefas-btn">
                        <a href="#addTarefa" data-toggle="modal" data-target="#addTarefa" class="btn btn-primary"><i class="fa fa-plus"></i> Solicitação</a>
                        <?php
                            echo ($this->session->userdata('tipo') == 1) ? '<a href="javascript:void()" title="Cadastrar Tipo de solicitação" class="btn btn-info" data-toggle="modal" data-target="#addTipo">Tipos de Solicitação</a>' : '';                                                        
                        ?>                        
                    </div>
                   
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                      <?php
                        // lista setores do funcionário
                        $i = 0;
                        $primeiro_setor = 0;
                        foreach($servicos as $ser):
                            // filtra setores por usuário
                            // ativa aba
                            if($i == 0){
                                $tabAtivo = 'class="active"';
                                $primeiro_setor = $ser->id;
                            }else{
                                $tabAtivo = '';
                            }
                            
                            echo '<li role="presentation" '.$tabAtivo.'><a href="#setor-'.$ser->id.'" aria-controls="paramim" role="tab" data-toggle="tab" onclick="lista_todastarefas('.$ser->id.')">'.$ser->nome.'</a></li>';
                            $i++;
                                                                                    
                        endforeach;
                      ?>                                              
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <?php
                            // lista abas dos setores responsáveis
                            $x = 0;
                            
                            foreach($servicos as $aba):
                                // filtra setores por usuário
                                // 0 = gestor
                                // > 0 = id do setor vinculado                                
                                    // ativa aba
                                    if($x == 0){                                        
                                        $abaAtiva = 'active';
                                    }else{
                                        $abaAtiva = '';
                                    }
                        ?>
                        <div role="tabpanel" class="tab-pane <?=$abaAtiva; ?>" id="setor-<?=$aba->id?>">
                            
                            <div class="tarefas-filtros">
                                <form method="post" class="form">
                                    <div class="row mt">
                                        <div class="col-md-6">
                                            <div class="btn-group" data-toggle="buttons">
                                                <label class="btn btn-primary active" onclick="lista_todastarefas(<?=$aba->id?>)">
                                                    <input type="radio" name="status" id="option2" class="muda_lista" value="1" autocomplete="off"> Todas
                                                </label>
                                                <label class="btn btn-primary" onclick="lista_tarefas(<?=$aba->id?>, 0)">
                                                    <input type="radio" name="status" id="option1" class="muda_lista" value="0" autocomplete="off" checked> Novas
                                                </label>
                                                <label class="btn btn-primary" onclick="lista_tarefas(<?=$aba->id?>, 1)">
                                                    <input type="radio" name="status" id="option2" class="muda_lista" value="1" autocomplete="off"> Trabalhando
                                                </label>                                        
                                                <label class="btn btn-primary" onclick="lista_tarefas(<?=$aba->id?>, 4)">
                                                    <input type="radio" name="status" id="option3" class="muda_lista" value="1" autocomplete="off"> Pausadas
                                                </label>                                                                                        
                                                <label class="btn btn-primary" onclick="lista_tarefas(<?=$aba->id?>, 3)">
                                                    <input type="radio" name="status" id="option5" class="muda_lista" value="3" autocomplete="off"> Entregues
                                                </label>                                        
                                            </div>                                    
                                        </div>

                                            <div class="col-md-6 text-right"> 
                                                <?php if($this->session->userdata('tipo') == 1): ?>
                                                <a href="javascript:void()" title="Filtrar tarefas" class="btn btn-default" data-toggle="collapse" data-target="#filtroTarefa<?=$aba->id?>" aria-expanded="false" aria-controls="filtroTarefa<?=$aba->id?>"><i class="glyphicon glyphicon-filter"></i> Filtrar tarefa</a>
                                                <?php endif; ?>
                                            </div>
                                        </div>                                                                       
                                </form>
                                
                                <div class="collapse" id="filtroTarefa<?=$aba->id?>" style="margin-top: 5px">
                                    <div class="well">
                                        <form method="post" class="form-inline" id="formFiltro<?=$aba->id?>">
                                            <div class="form-group">
                                                <label for="responsavel">Responsável</label><br />
                                                <select name="f_responsavel" class="form-control">
                                                    <?php
                                                        foreach($usuarios as $us):                                                            
                                                            echo '<option value="'.$us->id.'">'.$us->nome.'</option>';                                                            
                                                        endforeach;
                                                    ?>                                                    
                                                </select>
                                                <input type="hidden" name="setor" value="<?=$aba->id?>" />
                                            </div>
                                            <div class="form-group">
                                                <label for="p_inicial">Período inicial</label><br />
                                                <input type="text" class="form-control datepicker" name="p_inicial" value="<?=dataBR(SubData(date('Y-m-d'), 30, 0, 0)); ?>" />
                                            </div>
                                            <div class="form-group">
                                                <label for="p_final">Período Final</label><br />
                                                <input type="text" class="form-control datepicker" name="p_final" value="<?=date('d/m/Y'); ?>" />
                                            </div>
                                            <div class="form-group" style="padding-top: 23px;">
                                                <a href="javascript:void()" title="Filtrar tarefas" class="btn btn-default" onclick="filtrar_tarefa(<?=$aba->id?>)"><i class="glyphicon glyphicon-search"></i> Filtrar</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div><!--.tarefas-filtros-->
                            
                            <div class="msg_retorno"></div>
                            <div class="lista_tarefas"></div>
                            
                        </div><!--#paramim-->                        
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

<!-- Modal -->
<div class="modal fade" id="addTarefa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="<?=base_url('admin/tarefas/add'); ?>" method="post" enctype="multipart/form-data">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Nova Solicitação</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="titulo">Título*</label>
                    <input type="text" class="form-control" id="titulo" name="titulo" required />
                </div>
                <div class="form-group">
                    <label for="responsavel">Responsável*</label>
                    <select name="responsavel" class="form-control" id="responsavel">
                        <option value="">Selecione o responsável</option>
                        <?php
                            foreach($usuarios as $us):
                                // pré seleciona o usuário logado
                                if($this->session->userdata('id') == $us->id){
                                    $selectUser = 'selected';
                                }else{
                                    $selectUser = '';
                                }
                                
                                echo '<option value="'.$us->id.'" '.$selectUser.'>'.$us->nome.'</option>';
                            endforeach;
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="cliente">Cliente</label>
                    <select name="cliente" class="form-control" id="cliente" onchange="cliente_servico(this.value)">
                        <option value="">Selecione o cliente</option>
                        <?php
                            foreach($clientes as $cli):
                                echo '<option value="'.$cli->id_clientes.'">'.$cli->razao_social.'</option>';
                            endforeach;
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tipo">Tipo de solicitação*</label>
                    <div class="tipo_servico">
                    <select name="tipo" class="form-control" id="tipo">
                        <option value="">Selecione o tipo</option>
                        <?php
                            foreach($tipos as $tp):   
                                // se for gestor mostra tudo
                                if($this->session->userdata('servico') == 0){
                                    echo '<option value="'.$tp->id_categoria.'">'.$tp->nome.'</option>';
                                }else{
                                    // vincula as tarefas somente dos serviços.
                                    if($this->session->userdata('servico') == $tp->id_servico){
                                        echo '<option value="'.$tp->id_categoria.'">'.$tp->nome.'</option>';    
                                    }
                                    
                                }                                
                            endforeach;
                        ?>
                    </select>
                    </div><!--#setor-tarefas-->
                </div>
                
                <div class="form-group">
                    <label for="cliente">Descrição</label>
                    <textarea name="descricao" class="form-control"></textarea>
                </div>
                
                <div class="row mt">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="data-inicial">Data de Ínicio</label>
                            <input type="text" name="data_inicial" class="form-control datepicker" value="<?=date('d/m/Y');?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="data-inicial">Data de Finalização</label>
                            <input type="text" name="data_final" class="form-control datepicker" value="<?=date('d/m/Y'); ?>">
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
              <button type="submit" class="btn btn-primary">Cadastrar Solicitação</button>
            </div>
        </form>
    </div>
  </div>
</div>


<!-- Modal tipo de cliente-->
<div class="modal fade" id="addTipo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">        
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Tipos de Solicitações</h4>
      </div>
      <div class="modal-body">
          <div class="msg_return"></div>
          <form method="post" id="frmTipo">
              <div class="msg_return2"></div>
              <div class="form-group">
                  <label for="setor">Setor</label>
                  <select name="servico" class="form-control">
                    <?php
                        foreach($servicos as $srv):
                            echo '<option value="'.$srv->id.'">'.$srv->nome.'</option>';
                        endforeach;
                    ?>
                  </select>                 
              </div>
              <div class="form-group">
                  <label for="tipo">Tipo de solicitação</label>
                  <input type="text" name="tipo" id="tipo_tarefa" class="form-control" />
              </div>
              <div class="form-group">
                  <input type="hidden" name="id_tipo" id="id_tipo" />
                  <input type="button" class="btn btn-primary" id="btnAddTipo" value="Cadastrar">
                  <input type="button" class="btn btn-primary" id="btnUpTipo" value="Salvar">
                  <input type="button" class="btn btn-danger" id="btnCancelarUp" value="Cancelar">
                  <input type="button" class="btn btn-danger" id="btnDelTipo" value="Excluir permanentemente">
              </div>
          </form>
          <div id="tipos_tarefa"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>        
      </div>
    </form>
    </div>
  </div>
</div><!--#addTipo-->
<script type="text/javascript">
$(document).ready(function(){        
    $('#btnUpTipo').css('display', 'none');
    $('#id_tipo').css('display', 'none');
    $('#btnCancelarUp').css('display', 'none');
    $('#btnDelTipo').css('display', 'none');
    
    gera_tipo();    
    lista_todastarefas(<?=$primeiro_setor; ?>);
    
    // adiciona cliente
    $('#btnAddTipo').on('click', function(){
        var dados = $('#frmTipo').serialize();        
        $.ajax({
            type: 'POST',
            data: dados,
            url: '<?=base_url('/admin/tarefas/addtipo'); ?>',
            beforeSend: function(){
                $('.msg_return').html('<div class="alert alert-warning">Cadastrando tipo, aguarde...</div>');
            },
            success: function(e){
                $('.msg_return').html(e);
                $('#tipo_tarefa').val('');
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
            url: '<?=base_url('/admin/tarefas/edita_tipo'); ?>',
            beforeSend: function(){
                $('.msg_return').html('<div class="alert alert-warning">Salvando dados, aguarde...</div>');
            },
            success: function(e){
                $('.msg_return').html(e).fadeOut(5000);
                $('#btnUpTipo').css('display', 'none');
                $('#id_tipo').css('display', 'none').val('');
                $('#btnAddTipo').css('display', 'inline');
                $('#btnCancelarUp').css('display', 'none');
                $('#tipo_tarefa').val('');
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
            url: '<?=base_url('/admin/tarefas/excluir_tipo'); ?>',
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
                $('#tipo_tarefa').val('');
                $('#tipo_tarefa').removeAttr('disabled');
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
        $('#tipo_tarefa').val('');
        $('#tipo_tarefa').removeAttr('disabled');
        $('.msg_return2').html('');
    });
        
    // atualiza serviço conforme o usuário
    $('#responsavel').on('change', function(){
        var usuario = $(this).val();
        
        $.ajax({
            type: 'POST',
            data: {'usuario': usuario},
            url: '<?=base_url('/admin/tarefas/lista_setor_usuario'); ?>',
            beforeSend: function(){
                $('#setor-tarefas').html('Carregando tipos de tarefas, aguarde...');                
            },
            success: function(info){
                $('#setor-tarefas').html(info);
            },
            error: function(){
                $('#setor-tarefas').html('Erro ao carregar tarefas');
            }
        });
        
        $('#tipo').html(usuario);        
        
    });       
});


function gera_tipo(){
    $.ajax({
        type: 'GET',
        url: '<?=base_url('/admin/tarefas/tipos_tarefas'); ?>',
        beforeSend: function(){
            $('#tipos_tarefa').html('<div class="alert alert-warning">Atualizando tabela, aguarde...</div>');
        },
        success: function(e){
            $('#tipos_tarefa').html(e);
        },
        error: function(){
            $('#tipos_tarefa').html('<div class="alert alert-danger">Erro ao atualizar tipos de clientes, tente novamente.</div>');
        }
    });
}

function editar_tipo(id, tipo){    
    $('#btnUpTipo').css('display', 'inline');
    $('#id_tipo').css('display', 'block');
    $('#btnAddTipo').css('display', 'none');
    $('#btnCancelarUp').css('display', 'inline');
    $('#tipo_tarefa').val(tipo).focus();
    $('#id_tipo').val(id);
}

function excluir_tipo(id, tipo){
    $('#btnUpTipo').css('display', 'none');
    $('#btnDelTipo').css('display', 'inline');
    $('#id_tipo').css('display', 'block');
    $('#btnAddTipo').css('display', 'none');
    $('#btnCancelarUp').css('display', 'inline');
    $('#tipo_tarefa').val(tipo).focus().attr('disabled', 'disabled');
    $('#id_tipo').val(id);
    $('.msg_return2').html('<div class="alert alert-danger">Deseja realmente excluir o registro abaixo? Após exclusão, esta ação não poderá ser desfeita! Caso haja algum registro de clientes vinculadas ao registro, a ação de exclusão não será possível.</div>');
}
// lista todas as tarefas [RESPONSÁVEL]
function lista_todastarefas(setor){   
    
    $.ajax({
        type: 'POST',        
        data: {'setor':setor},
        url: '<?=base_url('/admin/tarefas/lista_tarefas'); ?>',
        beforeSend: function(){
            $('.msg_retorno').html('<div class="alert alert-warning">Carregando tarefas, aguarde...</div>');
        },
        success: function(info){
            $('.msg_retorno').html('');
            $('.lista_tarefas').html(info);
        },
        error: function(){
            $('.msg_retorno').html('<div class="alert alert-danger">Erro ao carregar tarefas, atualize sua página e tente novamente...</div>');
        }        
    });
}
// gera lista tarefas de acordo com o status [RESPONSÁVEL]
function lista_tarefas(setor, situacao){   

    $.ajax({
        type: 'POST',
        data: {'setor':setor, 'status': situacao},
        url: '<?=base_url('/admin/tarefas/tarefas_status'); ?>',
        beforeSend: function(){
            $('.msg_retorno').html('<div class="alert alert-warning">Carregando tarefas, aguarde...</div>');
        },
        success: function(info){
            $('.msg_retorno').html('');
            $('.lista_tarefas').html(info);
        },
        error: function(){
            $('.msg_retorno').html('<div class="alert alert-danger">Erro ao carregar tarefas, atualize sua página e tente novamente...</div>');
        }        
    });    
}



// deleta tarefa
function excluir_tarefa(tarefa){
    $.ajax({
        type: 'GET',
        url: '<?=base_url('/admin/tarefas/excluir_tarefa/'); ?>/'+tarefa,
        beforeSend: function(){
            $('msg_retorno').html('<div class="alert alert-warning">Aguarde, excluindo tarefa...</div>');
        },
        success: function(info){
            $('#modalTarefa').modal('hide');            
            lista_tarefas(0);
            $('.msg_retorno').html(info);
        },
        error: function(){
            $('.msg_retorno').html('<div class="alert alert-danger">Erro ao excluir tarefa, atualize sua página e tente novamente.</div>');
        }
    });
}

// filtrar tarefas
function filtrar_tarefa(setor){
    var dados = $('#formFiltro'+setor).serialize();
    
    $.ajax({
        type: 'POST',
        data: dados,
        url: '<?=base_url('/admin/tarefas/filtratarefa'); ?>',
        beforeSend: function(){
            $('msg_retorno').html('<div class="alert alert-warning">Aguarde, filtrando tarefas...</div>');
        },
        success: function(info){
            $('.msg_retorno').html('');
            $('.lista_tarefas').html(info);
        },
        error: function(){
            $('.msg_retorno').html('<div class="alert alert-danger">Erro ao carregar tarefas, atualize sua página e tente novamente...</div>');
        }   
    });
}

function cliente_servico(cliente){
    $.ajax({
        type: 'POST',
        data: {'cliente': cliente},
        url: '<?=base_url('/admin/tarefas/tarefas_clientes'); ?>',
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