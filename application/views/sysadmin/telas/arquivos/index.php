<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <h3><i class="fa fa-cloud-upload"></i> Gerenciamento de Documentos</h3>
        <div class="row mt">
            <div class="col-lg-12">
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
                <p> Documentos cadastrados no sistema.</p>
                <div class="margin10">
                    <a href="javascript:void()" title="Enviar Documento" class="btn btn-info" data-toggle="modal" data-target="#addArquivo">Enviar documento</a>
                    
                    <a class="btn btn-default" role="button" data-toggle="collapse" href="#formFiltros" aria-expanded="false" aria-controls="formFiltros"><i class="glyphicon glyphicon-filter"></i> Filtrar Resultados</a>
                </div>
                
                <div class="collapse" id="formFiltros">
                    <div class="well">
                        <div class="row"> 
                         <form id="formBusca" method="post" class="form">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="cliente">Cliente</label>
                                    <select name="cliente" class="form-control" onchange="cliente_servico(this.value)">
                                        <option value="">Selecione um cliente</option>
                                        <?php 
                                            foreach($clientes as $cli):
                                                echo '<option value="'.$cli->id_clientes.'">'.$cli->razao_social.'</option>';
                                            endforeach;
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="documento">Nome do Documento</label>
                                    <input type="text" name="documento" class="form-control" />
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="setor">Pasta de Documento</label>
                                    <div class="tipo_servico">
                                        <select name="servico" required class="form-control">
                                            <option value="">Selecione um cliente primeiro</option> 
                                        </select>
                                    </div> 
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" class="form-control">
                                        <option value="">Selecione um status</option>
                                        <option value="0">Não aberto</option>
                                        <option value="1">Aberto</option>
                                        <option value="2">Vencido</option>
                                        <option value="3">Excluído</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="cadastro">Data de Envio</label>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label for="cadastro">Data incial</label>
                                            <input type="text" class="form-control datepicker" name="envio_inicial" />
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="cadastro">Data final</label>
                                            <input type="text" class="form-control datepicker" name="envio_final" />                                    
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="cadastro">Validade do documento</label>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label for="cadastro">Data incial</label>
                                            <input type="text" class="form-control datepicker" name="validade_inicial" />
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="cadastro">Data final</label>
                                            <input type="text" class="form-control datepicker" name="validade_final" />                                    
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4" style="padding-top: 24px;">
                                <div class="form-group">                                
                                    <a href="javascript:void()" title="Filtrar registros" id="btnFiltrar" class="btn btn-default">Filtrar</a>
                                </div>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>                                                
                
                <div class="row">
                    <div class="col-lg-12">
                        
                        <table class="table table-hover table-striped" id="dataTables-documentos">
                            <thead>
                                <tr>
                                    <th>Data Envio</th>
                                    <th>Cliente</th>
                                    <th>Documento</th>
                                    <th>Setor > Pasta</th>
                                    <th>Enviado por</th>                                    
                                    <th>Validade do Documento</th>
                                    <th>Status</th>
                                    <th>Opções</th>
                                </tr>
                            </thead>
                        </table>
                        
                        
                    </div>
                </div>
                
            </div>
        </div>
    </section>
</section> 

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
        <a id="arquivo_lixeira" class="btn btn-warning"><i class="glyphicon glyphicon-trash"></i> Lixeira</a>
      </div>
    </form>
    </div>
  </div>
</div>
<script src="<?=base_url('layout/admin/js/jquery.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {                
    // tabela de protocolos    
    $('#dataTables-documentos').DataTable({  
        dom: 'Bfrtip',
        buttons: [
            'excel', 'pdf'
        ],
        processing: true,        
        ajax:{
            url: '<?=base_url('/admin/arquivos/listadocs'); ?>',
            type: "POST",
        },                   
        order: [[ 0, "desc" ]],                                
        columns: [
            { data:'dataenvio'},                        
            { data:'cliente'},                        
            { data:'documento'},
            { data:'setor'},
            { data:'responsavel'},
            { data:'validade'},
            { data:'status'},
            { data:'opc'}
        ]
    });     

    // tabela filtrada
    $('#btnFiltrar').on('click', function(){
        var dados = $('#formBusca').serialize();

        // tabela de cores
        var tab_docs = $('#dataTables-documentos').DataTable();    
        tab_docs.ajax.url( '/admin/arquivos/listadocs_filtro?' + dados ).load();   

        tab_docs.buttons().container().appendTo( $('.botoes:eq(0)', tab_docs.table().container() ) );

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

function deleta_arquivo(arquivo, nome, status){
    // modal de exclusão de clientes        
    var id = arquivo;    

    $('.nome_arquivo').html(nome);
    $('#id_arquivo').val(id);
    if(status == 3){
        $('#arquivo_lixeira').css('display', 'none');
    }else{
        $('#arquivo_lixeira').attr('href', '<?=base_url('/admin/arquivos/lixeira/'); ?>/'+id).css('display', 'inline');
    }                    
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