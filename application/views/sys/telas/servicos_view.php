<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <h3><i class="fa fa-suitcase"></i> Serviços</h3>
        <div class="row mt">
            <div class="col-lg-12">
                <p>Serviços cadastrados no sistema.</p>
                
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
                    
                    if(count($servicos) == 0){
                        echo '<pre>Não há serviços cadastrados no momento.</pre>';
                    }else{
                ?>
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Descrição</th>                            
                            <th></th>                            
                        </tr>
                    </thead>                    
                    <tbody>
                        <?php 
                            foreach($servicos as $ln): 
                                $iconServico = '<a href="javascript:void()" class="btn btn-info btn-sm info" data-toggle="popover" title="Serviços" data-content="Entre em contato para contratar"><i class="glyphicon glyphicon-info-sign"></i></a>';
                                if(count($cl_serv) > 0){
                                    foreach($cl_serv as $ser):
                                        if($ser->id_servicos == $ln->id){
                                            $iconServico = '<a href="javascript:void()" class="btn btn-success btn-sm info" data-toggle="popover" title="Serviços" data-content="Serviço já contratado"><i class="glyphicon glyphicon-ok-sign"></i></a>';
                                        }
                                    endforeach;
                                }
                        ?>
                        <tr>
                            <td>
                                <?=$ln->nome; ?>                                
                            </td>
                            <td>
                                <?=$ln->descricao; ?>                                
                            </td>                            
                            <td><?=$iconServico; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                    <?php } ?>
            </div>
        </div>
    </section><!--/wrapper -->
</section><!--/MAIN CONTENT -->