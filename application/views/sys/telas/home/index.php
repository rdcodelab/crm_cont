      <!--main content start-->
      <section id="main-content">
          <section class="wrapper">                           

              <div class="row">
                  <div class="col-lg-9 main-chart">                                    	
                      
                    <div class="row mt">                                            	
                      	<div class="col-md-12 col-sm-12 mb">
                            <div class="white-panel pn">
                                <div class="white-header">
                                    <h5>DOCUMENTOS QUE VENCEM HOJE (<?=date('d/m/Y'); ?>)</h5>
                                </div>
                                <div class="row form-arquivo">
                                    <?php 
                                        if(count($arquivos_novos) == 0){
                                            echo '<div class="alert alert-warning">Não há documentos à vencer hoje ('.date('d/m/Y').').</div>';
                                        }else{
                                    ?>
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>                                                
                                                <th>Tipo</th>
                                                <th>Documento</th>
                                                <th>Data Cadastro</th>
                                                <th>Data Vencimento</th>
                                                <th>Baixar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                foreach($arquivos_novos as $arq): 
                                                    // seleciona nome do tipo
                                                    foreach($tipos as $tp):
                                                        if($tp->id_tipo == $arq->id_tipo):
                                                            $tipo = $tp->nome_tipo;
                                                        endif;
                                                    endforeach;
                                            ?>
                                            <tr>
                                                <td><?=$tipo; ?></td>
                                                <td><?=$arq->titulo; ?></td>                                                
                                                <td><?=dataHora_BR($arq->data_cadastro); ?></td>
                                                <td><?=dataBR($arq->data_vencimento); ?></td>
                                                <td>
                                                    <a href="<?=base_url('uploads/arquivos/'.$arq->arquivo); ?>" title="Baixar arquivo" class="btn btn-default btn-sm" onclick="protocolo_download(<?=$arq->id_clientes; ?>, <?=$arq->idarquivos; ?>)" download><i class="fa fa-download"></i></a>                                                        
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>                                        
                                    </table>
                                    
                                    <div class="text-center margin10" style="margin: 10px;">
                                        <?=anchor('/arquivos', 'Ver todos', 'class="btn btn-default"'); ?>
                                    </div>
                                    <?php } ?>
                                </div>
	                      	
                            </div>                                                        
                            
                      	</div><!-- /col-md-4 -->                      	
                    </div><!-- /row -->
                    
                    <div class="row mt">                                            	
                      	<div class="col-md-12 col-sm-12 mb">
                            <div class="white-panel pn">
                                <div class="white-header">
                                    <h5>DOCUMENTOS NÃO ABERTOS</h5>
                                </div>
                                <div class="row form-arquivo">
                                    <?php 
                                        if(count($arquivos_novos) == 0){
                                            echo '<div class="alert alert-warning">Não há documentos cadastrados.</div>';
                                        }else{
                                    ?>
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>                                                
                                                <th>Tipo</th>
                                                <th>Documento</th>
                                                <th>Data Cadastro</th>
                                                <th>Data Vencimento</th>
                                                <th>Baixar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                foreach($arquivos_novos as $arq): 
                                                    // seleciona nome do tipo
                                                    foreach($tipos as $tp):
                                                        if($tp->id_tipo == $arq->id_tipo):
                                                            $tipo = $tp->nome_tipo;
                                                        endif;
                                                    endforeach;
                                            ?>
                                            <tr>
                                                <td><?=$tipo; ?></td>
                                                <td><?=$arq->titulo; ?></td>                                                
                                                <td><?=dataHora_BR($arq->data_cadastro); ?></td>
                                                <td><?=dataBR($arq->data_vencimento); ?></td>
                                                <td>
                                                    <a href="<?=base_url('uploads/arquivos/'.$arq->arquivo); ?>" title="Baixar arquivo" class="btn btn-default btn-sm" onclick="protocolo_download(<?=$arq->id_clientes; ?>, <?=$arq->idarquivos; ?>)" download><i class="fa fa-download"></i></a>                                                        
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>                                        
                                    </table>
                                    
                                    <div class="text-center margin10" style="margin: 10px;">
                                        <?=anchor('/arquivos', 'Ver todos', 'class="btn btn-default"'); ?>
                                    </div>
                                    <?php } ?>
                                </div>
	                      	
                            </div>                                                        
                            
                      	</div><!-- /col-md-4 -->                      	
                    </div><!-- /row -->
                    
                     
                <div class="row mt">
                        <div class="col-md-12 col-sm-12">
                            
                              <div class="white-panel pn">
                                <div class="white-header">
                                    <h5 class="tituloHome"><a href="javascript:void()" name="tarefaCliente">SOLICITAÇÕES DE SERVIÇOS EM ABERTO</a></h5>
                                </div>
                                <div class="row form-arquivo"> 
                                    
                                    <table class="table table-hover tarefas">
                                        <tbody>
                                            <?php
                                            
                                                if(count($tarefas_clientes) == 0){
                                                    echo '<pre style="margin: 10px;">Não há tarefas solicitadas no momento.</pre>';
                                                }else{
                                                    foreach($tarefas_clientes as $trfc):
                                                        
                                                        $cliente = $this->clientes->lista_cliente_id($trfc->id_cliente);                                                                                                                
                                                        $categoria = $this->tarefas->lista_tipo_id($trfc->tarefas_categorias_id_categoria);                                                        

                                                        // formata status
                                                        switch ($trfc->status){
                                                            case 0:
                                                                $status_tarefa = '<div class="label label-default">Não atendido</div>';
                                                            break;
                                                            case 1:
                                                                $status_tarefa = '<div class="label label-info">Trabalhando</div>';
                                                            break;
                                                            case 2:
                                                                $status_tarefa = '<div class="label label-primary">Avaliando</div>';
                                                            break;
                                                            case 3:
                                                                $status_tarefa = '<div class="label label-success">Entregue</div>';
                                                            break;
                                                            case 4:
                                                                $status_tarefa = '<div class="label label-warning">Pausada</div>';
                                                            break;
                                                        }     

                                                        // monta botão de status de acordo com o status da tarefa
                                                        // 0 = mostra botão de iniciar tarefa
                                                        // 1 = Mostra botão de pausar tarefa
                                                        // 2 = Mostra o botão de avaliando
                                                        // 3 = Mostra botão de entregue e desabilita demais
                                                        // 4 = Mostra botão de tarefa pausada
                                                        switch ($trfc->status){
                                                            case 0:
                                                                $btnStatus = '<a href="'.base_url('/admin/tarefas/status_tarefa/'.$trfc->id_tarefa.'/1').'" title="Trabalhar Tarefa" id="btnAcao" class="btn btn-info btn-sm"><i class="glyphicon glyphicon-play"></i> Íniciar</a>';
                                                            break;
                                                            case 1:
                                                                $btnStatus = '<a href="'.base_url('/admin/tarefas/status_tarefa/'.$trfc->id_tarefa).'/4" title="Pausar Tarefa" id="btnAcao" class="btn btn-warning btn-sm"><i class="glyphicon glyphicon-pause"></i> Pausar</a>';
                                                            break;
                                                            case 2:
                                                                $btnStatus = '<a href="'.base_url('/admin/tarefas/status_tarefa/'.$trfc->id_tarefa.'/1').'" title="Avaliando Tarefa" id="btnAcao" class="btn btn-info btn-sm"><i class="glyphicon glyphicon-unchecked"></i> Avaliando</a>';
                                                            break;
                                                            case 3:
                                                                $btnStatus = '<a href="javascript:void()" title="Tarefa entregue" id="btnAcao" class="btn btn-success" disabled><i class="glyphicon glyphicon-check btn-sm"></i> Entregue</a>';
                                                            break;
                                                            case 4:
                                                                $btnStatus = '<a href="'.base_url('/admin/tarefas/status_tarefa/'.$trfc->id_tarefa.'/1').'" title="Re-iniciar tarefa" id="btnAcao" class="btn btn-info btn-sm"><i class="glyphicon glyphicon-play"></i> Trabalhar</a>';
                                                            break;
                                                        }
                                                        
                                                        // verifica se a tarefa esta atrasada ou não
                                                        if($trfc->data_validade < date('Y-m-d') && $trfc->data_validade != '0000-00-00'){
                                                            $valTarefa = '<span class="label label-danger">Tarefa atrasada</span>';
                                                            $classTarefa = 'class="text-danger"';
                                                        }else{
                                                            $valTarefa = '';
                                                            $classTarefa = '';
                                                        }
                                            ?>
                                                        <tr <?=$classTarefa; ?>>                        
                                                            <td>
                                                                <h4><a href="javascript:void()" title="Abrir tarefa" onclick="abre_tarefa(<?=$trfc->id_tarefa; ?>)"><?=$trfc->id_tarefa; ?> - <?=$trfc->titulo; ?></a></h4>
                                                                <?=$cliente->razao_social.' > '.$categoria->nome; ?>
                                                            </td>
                                                            <td>
                                                                Começa em: <?=dataHora_BR($trfc->data_inicio); ?><br />
                                                                Finaliza em: <?=($trfc->data_validade == '0000-00-00') ? 'N/d' : dataHora_BR($trfc->data_validade); ?><br />
                                                                <?=$valTarefa; ?>
                                                            </td>
                                                            <td><?=$status_tarefa; ?></td>                                                    
                                                                <?php
                                                                    if($trfc->usuarios_responsaveis == $this->session->userdata('id')){
                                                                       echo "<td>".$btnStatus."</td>"; 
                                                                    }                                
                                                                ?>                        
                                                        </tr>
                                            <?php 
                                                    endforeach;
                                                } ?>
                                        </tbody>                                
                                    </table>
                                    
                                </div>
                              </div>
                        </div>
                    </div>
                
                    
 	
                </div><!-- /col-lg-9 END SECTION MIDDLE -->
                  
                  
      <!-- **********************************************************************************************************************************************************
      RIGHT SIDEBAR CONTENT
      *********************************************************************************************************************************************************** -->                  
                  
                <div class="col-lg-3 ds" style="margin-top: 53px;">
                    <!--COMPLETED ACTIONS DONUTS CHART-->
                    <h3>ENVIAR DOCUMENTOS</h3>
                    <div class="margin10">
                        <form method="post"  action="<?=base_url('/arquivos/upload_arquivos')?>" id="form_up_imagem_post" enctype="multipart/form-data">
                            <div class="uploadFile">
                                <input type="file" name="docs[]" id="file" class="inputfile" multiple />
                                <label for="file"><strong><i class="fa fa-upload"></i> Selecione os documentos</strong></label>
                            </div>
                            <div class="progresso" style="display: none; width: 95%; margin: 10px auto">
                                <progress value="0" max="100" class="form-control" style="width: 100%"></progress><span id="porcentagem">0%</span>
                            </div>                                   

                        </form>
                      
                        <form class="frmDados margin10" id="formDocExpress">


                            <div class="modal-body">
                                <div class="msg_return"></div>
                                <div id="aviso_upload"></div> 

                                <div class="form-group">
                                    <label for="documentos">Documentos Selecionados</label>
                                    <div class="retorno_docs">
                                        <div class="alert alert-warning">Clique no campo acima para selecionar os documentos.</div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="tipo">Tipo de documento</label>
                                    <div class="tipo_servico">
                                        <select name="servicos" required class="form-control servicos_cliente">
                                            <option value="">Selecione o tipo de documento</option>
                                            <?php
                                                foreach($tipos as $tp):
                                                    echo '<option value="'.$tp->id_tipo.'">'.$tp->nome_tipo.'</option>';
                                                endforeach;
                                            ?>
                                        </select>
                                    </div>                        
                                </div>

                            </div>
                          <div class="modal-footer">                         
                            <div class="msg_envio"></div>
                            <a href="javascript:void()" title="Enviar Documentos" class="btn btn-primary btnEnvia" onclick="enviaDocumentos()">Enviar</a>
                            <input type="hidden" name="referencia" value="2" />
                            <input type="hidden" name="usuario" value="<?=$this->session->userdata('id'); ?>" />
                            <input type="hidden" name="cliente" value="<?=$this->session->userdata('id_cliente'); ?>" />
                          </div>    

                        </form>
                        
                    </div>
                    
                  </div><!-- /col-lg-3 -->
                  
                <div class="col-lg-3 ds" style="margin-top: 53px;">
                    <!--COMPLETED ACTIONS DONUTS CHART-->
                    <h3>CHAMADOS</h3>
                      
                   <?php
                        if(count($chamados) == 0){
                            echo '<div class="alert alert-warning">Você ainda não solicitou nenhum atendimento.</div>';
                        }else{
                            foreach($chamados as $atendimento):
                                                                
                    ?>
                                <!-- First Action -->
                                <div class="desc">
                                  <div class="thumb">
                                      <span class="badge bg-theme"><i class="fa fa-clock-o"></i></span>
                                  </div>
                                  <div class="details">
                                      <p><muted><?=dataHora_BR($atendimento->data_cadastro); ?></muted><br/>
                                      <a href="#"><?=$this->session->userdata('nome_cliente'); ?></a><br/> 
                                        <?=anchor('/chamados/ver/'.$atendimento->id_chamados, '<i class="fa fa-envelope"></i> '.$atendimento->assunto); ?>
                                      </p>
                                  </div>
                                </div>
                    <?php 
                            endforeach;
                        } 
                    ?>                      
                  </div><!-- /col-lg-3 -->
              </div><!--/row -->
          </section>
      </section>

      <!--main content end-->
      
      
<script src="<?=base_url('layout/admin/js/jquery.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
    // upload de fotos de produtos
    $("#file").on("change",function(){
        $("#aviso_upload").removeClass().addClass("alert alert-info").html("Enviando documentos, aguarde...");
        $('.progresso').css('display', 'block');          
        $('.uploadFile').css('display', 'none');

        if(validaExtensao("file")){
          $("#form_up_imagem_post").ajaxForm({
              uploadProgress: function(event, position, total, percentComplete) {
                  $('progress').attr('value',percentComplete);
                  $('#porcentagem').html(percentComplete+'%');
              }, 
              success: function(response){
                  $("#aviso_upload").removeClass().addClass("alert alert-success").html('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> Documento enviado com sucesso!');
                  $(".retorno_docs").html(response);
                  $("#file").val("").empty();
                  $('progress').attr('value',0);
                  $('#porcentagem').html('0%');                    
                  $('.uploadFile').css('display', 'block');
                  $('.progresso').css('display', 'none');
                  $('.btnAcoes').css('display', 'block');                    
              }
            }).submit();

        }

        return false;        
    }); 
    
});    

// envia documentos
function enviaDocumentos(){
    var dados = $('#formDocExpress').serialize();
    
    var tipo = $('.servicos_cliente').val();
    
    if(tipo == ""){
        $('.msg_envio').html('<div class="alert alert-danger">Selecione um tipo de arquivo.</div>');
        $('.servicos_cliente').focus();
    }else{
        $.ajax({
            type: 'POST',
            data: dados,
            url: '<?=base_url('/arquivos/envia_lote'); ?>',
            beforeSend: function(){
                $('.msg_envio').html('<div class="alert alert-warning">Aguarde, enviando arquivos...</div>');
                $('.btnEnvia').attr('disabled', 'disabled');
            },
            success: function(info){
                $('.msg_envio').html('<div class="alert alert-success">Arquivos enviados com sucesso.</div>');                        
                $('.btnEnvia').removeAttr('disabled', 'disabled');            
                setTimeout(location.reload(), 5000);
            },
            error: function(){
                $('.msg_envio').html('<div class="alert alert-danger">Erro ao enviar arquivos, tente novamente.</div>');            
                $('.btnEnvia').removeAttr('disabled', 'disabled');
            }
        });
    }
}

// valida extensão da imagem
// id = parametro com tipo de extensão da imagem
function validaExtensao(id){
            
    var extensoes = new Array("bmp","jpg","jpeg","png", "pdf", "doc", "xls", "docx", "txt");

    var ext = $("#"+id).val().split(".")[1].toLowerCase();

    if($.inArray(ext, extensoes) == -1){
        alert("Arquivo não permitido: "+ext);
        $("#"+id).val("").empty();
        return false;
    }else{
        return true;
    }
}

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
</script>