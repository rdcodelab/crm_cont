<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <h3><i class="fa fa-envelope-o"></i> Solicitação de Chamados</h3>
        <div class="row mt">
            <div class="col-lg-12">
                <p>Chamados abertos por clientes</p>
                
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
                
                <div class="margin10">                                        
                    <a class="btn btn-default" role="button" data-toggle="collapse" href="#formFiltros" aria-expanded="false" aria-controls="formFiltros"><i class="glyphicon glyphicon-filter"></i> Filtrar Chamados</a>
                </div>
                
                <div class="collapse" id="formFiltros">
                    <div class="well">
                        <div class="row"> 
                         <form id="formBusca" method="post" class="form">
                            <div class="col-lg-3">
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
                                    <label for="documento">Nome do Chamado</label>
                                    <input type="text" name="chamado" class="form-control" />
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="setor">Setor</label>
                                    <div class="tipo_servico">
                                        <select name="tipo" required class="form-control">
                                            <option value="">Selecione um Setor</option> 
                                            <?php
                                                foreach($servicos as $srv):
                                                    echo '<option value="'.$srv->id.'">'.$srv->nome.'</option>';
                                                endforeach;
                                            ?>
                                        </select>
                                    </div> 
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" class="form-control">
                                        <option value="">Selecione um status</option>
                                        <option value="0">Novo</option>
                                        <option value="1">Pendente</option>
                                        <option value="2">Fechado</option>                                        
                                    </select>
                                </div>
                            </div>
                             
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="urgencia">Nível de Urgência</label>
                                    <select name="prioridade" class="form-control">
                                        <option value="">Selecione a prioridade</option>
                                        <option value="0">Baixa</option>
                                        <option value="1">Normal</option>
                                        <option value="2">Alta</option>                                        
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="cadastro">Data de Abertura</label>
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
                            
                            <div class="col-lg-4" style="padding-top: 24px;">
                                <div class="form-group">                                
                                    <a href="javascript:void()" title="Filtrar registros" id="btnFiltrar" class="btn btn-default">Filtrar</a>
                                </div>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>                                                
                
                <div>

                    <table class="table table-hover table-striped" id="dataTables-chamados">
                        <thead>
                            <tr>
                                <th>Número</th>
                                <th>Cliente</th>
                                <th>Usuário</th>
                                <th>Setor</th>
                                <th>Assunto</th>
                                <th>Prioridade</th>
                                <th>Data Abertura</th>
                                <th>Status</th>
                                <th>Opções</th>
                            </tr>
                        </thead>                                            
                    </table>                          
                </div>
            </div>
        </div>
    </section><!--/wrapper -->
</section><!--/MAIN CONTENT -->
<script src="<?=base_url('layout/admin/js/jquery.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {                
    // tabela de protocolos    
    $('#dataTables-chamados').DataTable({  
        dom: 'Bfrtip',
        buttons: [
            'excel', 'pdf'
        ],
        processing: true,        
        ajax:{
            url: '<?=base_url('/admin/chamados/listachamados'); ?>',
            type: "POST",
        },                   
        order: [[ 0, "desc" ]],                                
        columns: [
            { data:'numero'},                        
            { data:'cliente'},                        
            { data:'usuario'},
            { data:'setor'},
            { data:'assunto'},
            { data:'prioridade'},
            { data:'data_abertura'},
            { data:'status'},
            { data:'opc'}
        ]
    });     

    // tabela filtrada
    $('#btnFiltrar').on('click', function(){
        var dados = $('#formBusca').serialize();        
        // tabela de cores
        var tab_docs = $('#dataTables-chamados').DataTable();    
        tab_docs.ajax.url( '/admin/chamados/listachamados_filtro?' + dados ).load();   

        tab_docs.buttons().container().appendTo( $('.botoes:eq(0)', tab_docs.table().container() ) );

    });
});

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