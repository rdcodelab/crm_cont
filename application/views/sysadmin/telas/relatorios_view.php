<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <h3><i class="fa fa-user"></i> Relatórios</h3>
        <div class="row mt">
            <div class="col-lg-12">                                
                <?php
                    if($this->session->flashdata('error')){
                        echo '<div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>                                            
                                '.$this->session->flashdata('error').'
                              </div>';
                    }
                    if($this->session->flashdata('sucesso')){
                        echo '<div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>                                            
                                '.$this->session->flashdata('sucesso').'
                              </div>';
                    }
                ?>
                
                <div class="margin10">
                <a class="btn btn-default" role="button" data-toggle="collapse" href="#filtro" aria-expanded="false" aria-controls="filtro">
                    <i class="glyphicon glyphicon-filter"></i> Filtrar Registros
                </a>
                
                <div class="collapse margin5" id="filtro">
                  <div class="well">
                      <form class="form" id="formBusca">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                  <label for="tipoRelatorio">Tipo de Relatório</label>
                                  <select name="tipoRelatorio" id="tipoRelatorio" class="form-control">
                                      <option value="1">Relatório de Clientes</option>
                                      <option value="2">Relatório de Funcionários</option>
                                  </select>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                  <label for="periodoInicial">Período Inicial</label>
                                  <input type="text" name="periodoInicial" class="form-control datepicker" id="periodoInicial" value="<?=implode("/", array_reverse(explode("-", SubData(date('Y-m-d'), 30, 0, 0)))); ?>">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                  <label for="periodoFinal">Período Final</label>
                                  <input type="text" name="periodoFinal" class="form-control datepicker" id="periodoFinal" value="<?=date('d/m/Y'); ?>">
                                </div>
                            </div>
                            <div class="col-lg-3 filtro_cliente">
                                <div class="form-group">
                                  <label for="cliente">Cliente</label>
                                  <select name="cliente" class="form-control">
                                      <option value="0">Todos os clientes</option>
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
                                  <label for="sessao">Módulos</label>
                                  <select name="sessao" class="form-control">
                                      <option value="0">Todas as sessões</option>
                                      <option value="1">Documentos</option>                                  
                                      <option value="2">Usuários</option>
                                      <option value="3">Clientes</option>
                                      <option value="4">Chamados</option>                                                                                                                                                                        
                                      <option value="8">Tarefas</option>                                                                                                                                                                        
                                  </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                  <label for="sessao">Setores</label>
                                  <select name="setores" class="form-control">
                                      <option value="">Todas os setores</option>
                                      <?php
                                        foreach($servicos as $srv):
                                            echo '<option value="'.$srv->id.'">'.$srv->nome.'</option>';
                                        endforeach;
                                      ?>
                                  </select>
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <div class="form-group">                                
                                    <a href="javascript:void()" title="Filtrar registros" id="btnFiltrar" class="btn btn-default">Filtrar</a>
                                </div>
                            </div>
                        </div>                        

                    </form>
                  </div>
                </div>                                                
                </div><!--.margin10-->
                <div class="botoes"></div>
                <table class="table table-striped table-bordered table-hover display" cellspacing="0" width="100%" id="dataTables-protocolos2">
                    <thead>
                        <tr>
                            <th>Data Registro</th>                                                                                                                                                          
                            <th>Cliente/ Usuário</th>                                                                                                                                                          
                            <th>Setor</th>                                                                                        
                            <th>Módulo</th>                                                                                        
                            <th>Ação</th>                                                                                        
                            <th>IP</th>                                                                                                                                
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>                                
                
                
            </div><!--.col-lg-12-->
        </div><!--.row-->
    </section><!--.wrapper-->
</section><!--#main-content-->

<script type="text/javascript">
$(document).ready(function() {                

    // tabela de protocolos    
    $('#dataTables-protocolos2').DataTable({  
        dom: 'Bfrtip',
        buttons: [
            'excel', 'pdf'
        ],
        processing: true,        
        ajax:{
            url: '<?=base_url('/admin/protocolos/listalogs'); ?>',
            type: "POST",
        },                   
        order: [[ 0, "desc" ]],                                
        columns: [
            { data:'datareg'},                        
            { data:'usuario'},                        
            { data:'setor'},
            { data:'sessao'},
            { data:'acao'},
            { data:'ip'}                  
        ]
    });     

    // tabela filtrada
    $('#btnFiltrar').on('click', function(){
        var dados = $('#formBusca').serialize();

        // tabela de cores
        var tab_produtos = $('#dataTables-protocolos2').DataTable();    
        tab_produtos.ajax.url( '/admin/protocolos/listalogs_filtro?' + dados ).load();   

        tab_produtos.buttons().container().appendTo( $('.botoes:eq(0)', tab_produtos.table().container() ) );


    });        
});        
</script>