<div class="modal_tarefa"></div>
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <h3><i class="fa fa-list-ul"></i> Solicitações de Serviços</h3>
        <div class="row mt">
            <div class="col-lg-12"> 
                <div class="margin10">
                    <a href="#addTarefa" data-toggle="modal" data-target="#addTarefa" class="btn btn-primary">Solicitar serviços</a>
                </div>
                
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
                    
                    <table class="table table-hover table-striped" id="dataTables-tarefas">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Título</th>
                                <th>Setor</th>
                                <th>Tipo de Tarefa</th>                                
                                <th>Status</th>
                                <th>Progresso</th>
                            </tr>
                        </thead>
                    </table>
                                                          
                </div>                                
                
            </div>
        </div>
    </section><!--/wrapper -->
</section><!--/MAIN CONTENT -->

<!-- Modal -->
<div class="modal fade" id="addTarefa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="<?=base_url('/tarefas/add'); ?>" method="post" enctype="multipart/form-data">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Nova Solicitação</h4>
            </div>
            <div class="modal-body">                
                <div class="form-group">
                    <label for="tipo">Tipo de solicitação*</label>
                    <div id="setor-tarefas">
                    <select name="tipo" class="form-control" id="tipo" required>
                        <option value="">Selecione o tipo</option>
                        <?php
                            foreach($tipos as $tp):                                                                       
                                echo '<option value="'.$tp->id_categoria.'">'.$tp->nome.' > '.$tp->categoria.'</option>';                                                                    
                            endforeach;
                        ?>
                    </select>
                    </div><!--#setor-tarefas-->
                </div>
                <div class="form-group">
                    <label for="titulo">Título*</label>
                    <input type="text" class="form-control" id="titulo" name="titulo" required />
                </div>                
                <div class="form-group">
                    <label for="cliente">Cliente</label>
                    <input type="text" class="form-control" value="<?=$this->session->userdata('nome_cliente'); ?>" disabled />
                    <input type="hidden" name="cliente" value="<?=$this->session->userdata('id_cliente'); ?>" />                    
                </div>
                <div class="form-group">
                    <label for="cliente">Descrição</label>
                    <textarea name="descricao" class="form-control"></textarea>
                </div>
                
                <div class="row mt">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="data-inicial">Data de Ínicio</label>
                            <input type="text" name="data_inicial" class="form-control" value="<?=date('d/m/Y');?>" disabled>
                            <input type="hidden" name="data_inicial" value="<?=date('d/m/Y');?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        
                    </div>
                </div>
                
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
              <button type="submit" class="btn btn-primary">Cadastrar Solicitação</button>
              <input type="hidden" name="origem" value="1" />
            </div>
        </form>
    </div>
  </div>
</div>

<script src="<?=base_url('/layout/admin/js/jquery.js'); ?>" type="text/javascript"></script>
<script src="<?=base_url('/layout/admin/js/datepicker/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('.datepicker').datepicker({
        format:'dd/mm/yyyy',
        locale: 'pt-BR'
    });
    
    // tabela de protocolos    
    $('#dataTables-tarefas').DataTable({      
        processing: true,        
        ajax:{
            url: '<?=base_url('/tarefas/listatarefas'); ?>',
            type: "POST",
        },                   
        order: [[ 0, "desc" ]],                                
        columns: [
            { data:'cod'},                        
            { data:'titulo'},                        
            { data:'setor'},                        
            { data:'tipo'},            
            { data:'status'},                  
            { data:'progresso'}                  
        ]
    });  
                            
           
    // atualiza serviço conforme o usuário
    $('#responsavel').on('change', function(){
        var usuario = $(this).val();
        alert(usuario);
        $.ajax({
            type: 'POST',
            data: {'usuario': usuario},
            url: '<?=base_url('/tarefas/lista_setor_usuario'); ?>',
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
</script> 