<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <h3><i class="fa fa-envelope-o"></i> Chamados</h3>
        <div class="row mt">
            <div class="col-lg-12">
                <p>Solicitações de suporte via chamados</p>
                    
                <div class="margin10">
                    <a href="javascript:void()" title="Abrir Solicitação" data-target="#addChamado" data-toggle="modal" class="btn btn-primary btn-sm">Solicitar suporte</a>
                </div><!--.margin10-->
                
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
                    
                    if(count($chamados) == 0){
                        echo '<pre>Você não abriu nenhuma solicitação até o momento.</pre>';
                    }else{
                ?>
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Número</th>
                            <th>Data Abertura</th>
                            <th>Assunto</th>                            
                            <th>Urgência</th>                            
                            <th>Status</th>                            
                            <th></th>                            
                        </tr>
                    </thead>                    
                    <tbody>
                        <?php 
                            foreach($chamados as $ln):                                 
                                // define nível de urgência do chamado
                                switch ($ln->nivel_urgencia){
                                    case 0:
                                        $urgencia = '<span class="label label-default">Baixa</span>';
                                    break;
                                    case 1:
                                        $urgencia = '<span class="label label-info">Normal</span>';
                                    break;
                                    case 2:
                                        $urgencia = '<span class="label label-warning">Alta</span>';
                                    break;
                                }
                                // define status do chamado
                                switch ($ln->status_chamado){
                                    case 0:
                                        $status_chamado = '<span class="label label-info">Novo</span>';
                                    break;
                                    case 1:
                                        $status_chamado = '<span class="label label-warning">Pendente</span>';
                                    break;
                                    case 2:
                                        $status_chamado = '<span class="label label-success">Respondido</span>';
                                    break;
                                }
                        ?>
                        <tr>
                            <td>#<?=$ln->id_chamados; ?></td>
                            <td><?=dataHora_BR($ln->data_cadastro); ?></td>
                            <td><?=$ln->assunto; ?></td>                            
                            <td><?=$urgencia; ?></td>
                            <td><?=$status_chamado; ?></td>
                            <td>
                                <a href="<?=base_url('/chamados/ver/'.$ln->id_chamados); ?>" title="Visualizar chamado" class="btn btn-info"><i class="glyphicon glyphicon-eye-open"></i></a>
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

<!-- Modal -->
<div class="modal fade" id="addChamado" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="<?=base_url('/chamados/add'); ?>" method="post" enctype="multipart/form-data">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Abrir nova solicitação</h4>
      </div>
      <div class="modal-body">
          <div class="form-group">
              <label for="nome">Cliente</label>
              <input type="text" class="form-control" value="<?=$this->session->userdata('nome_cliente'); ?>" disabled  />
          </div>
          
          <div class="form-group">
              <label for="nome">Seu nome</label>
              <input type="text" class="form-control" value="<?=$this->session->userdata('nome'); ?>" disabled  />
          </div>
          
          <div class="form-group">
              <label for="servico">Setor*</label>
              <select name="servico" id="servico" class="form-control" required>
                  <option value="">Selecione um setor</option>
                  <?php
                    foreach($servicos as $ser){
                        echo '<option value="'.$ser->id_servicos.'">'.$ser->nome.'</option>';
                    }
                  ?>
              </select>
          </div>
          
          <div class="form-group">
              <label for="urgencia">Urgência</label>
              <select name="urgencia" class="form-control">
                  <option value="0">Baixa</option>
                  <option value="1">Normal</option>
                  <option value="2">Alta</option>
              </select>
          </div>
          
          <div class="form-group">
              <label for="assunto">Assunto</label>
              <input type="text" class="form-control" name="assunto" placeholder="Assunto desejado" required />
          </div>
          
          <div class="form-group">
              <label for="nome">Mensagem</label>
              <textarea class="form-control" name="mensagem" placeholder="No que podemos ajudar?"></textarea>
          </div>          
          
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary">Cadastrar</button>        
      </div>
    </form>
    </div>
  </div>
</div>