<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <h3><i class="fa fa-user"></i> Usuários - Logs e Protocolos</h3>
        <div class="row mt">
            <div class="col-lg-12">
                <p>Logs e Protocolos do usuário <?=$usuario->nome; ?>.</p>
                
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

                
                <table class="table table-striped table-bordered table-hover" id="dataTables-protocolos">
                    <thead>
                        <tr>
                            <th>Data Registro</th>                                                                                                                                                          
                            <th>Sessão</th>                                                                                        
                            <th>Ação</th>                                                                                        
                            <th>IP</th>                                                                                                                                
                        </tr>
                    </thead>

                </table>
                
                <?php echo anchor('admin/usuarios', 'Voltar', 'class="label label-default"'); ?>
                
                
            </div><!--.col-lg-12-->
        </div><!--.row-->
    </section><!--.wrapper-->
</section><!--#main-content-->
<script src="<?=base_url('layout/admin/js/jquery.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
    // tabela de protocolos    
    $('#dataTables-protocolos').DataTable({

        'ajax':'<?=base_url('/admin/protocolos/listalogs_usuarios/'.$usuario->id); ?>',                   
        'order': [[ 0, "desc" ]],                                
        'columns': [
            { 'data':'datareg'},                        
            { 'data':'sessao'},
            { 'data':'acao'},
            { 'data':'ip'}                  
        ],

    }); 
});        
</script>