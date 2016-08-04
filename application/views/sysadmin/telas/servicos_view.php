<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <h3><i class="fa fa-suitcase"></i> Setores</h3>
        <div class="row mt">
            <div class="col-lg-12">
                <p>Setores cadastrados no sistema.</p>
                <p>                    
                    <a href="javascript:void()" title="Cadastrar setor" class="btn btn-info" data-toggle="modal" data-target="#addServico">Cadastrar setor</a>
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
                    
                    if(count($servicos) == 0){
                        echo '<pre>Não há setores cadastrados no momento.</pre>';
                    }else{
                ?>
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Status</th>
                            <th>Opções</th>
                        </tr>
                    </thead>                    
                    <tbody>
                        <?php foreach($servicos as $ln): ?>
                        <tr>
                            <td>
                                <?=$ln->nome; ?>
                                <input type="hidden" class="nome_servico_<?=$ln->id; ?>" value="<?=$ln->nome; ?>">
                            </td>
                            <td>
                                <?=$ln->descricao; ?>
                                <input type="hidden" class="desc_servico_<?=$ln->id; ?>" value="<?=$ln->descricao; ?>">
                            </td>
                            <td><?=($ln->status == 1) ? '<div class="label label-success">Ativo</div>' : '<div class="label label-danger">Inativo</div>'?></td>
                            <td>
                                <a href="javascript:void()" title="Editar dados" class="btn btn-info edita_servico" data-toggle="modal" data-id="<?=$ln->id; ?>" data-target="#editaServico"><i class="fa fa-edit"></i></a>
                                <a href="javascript:void()" title="Excluir dados" class="btn btn-danger delServico" data-toggle="modal" data-nome="<?=$ln->nome; ?>" data-id="<?=$ln->id; ?>" data-target="#delServico"><i class="fa fa-trash-o"></i></a>
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
<div class="modal fade" id="addServico" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="<?=base_url('admin/servicos/add'); ?>" method="post" enctype="multipart/form-data">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Cadastro de Serviços</h4>
      </div>
      <div class="modal-body">
          <div class="form-group">
              <label for="nome">Nome*</label>
              <input type="text" name="nome" class="form-control" placeholder="Nome do setor" required />
          </div>
          <div class="form-group">
              <label for="nome">Descrição</label>
              <textarea class="form-control" name="descricao"></textarea>
          </div>
          <div class="form-group">
              <label for="tipo">Status</label>
              <select name="status" class="form-control">
                  <option value="1">Ativo</option>
                  <option value="0">inativo</option>
              </select>
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

<!-- Modal Editar -->
<div class="modal fade" id="editaServico" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="<?=base_url('admin/servicos/editar'); ?>" method="post" enctype="multipart/form-data">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Editar de Setor</h4>
      </div>
      <div class="modal-body">
          <div class="form-group">
              <label for="nome">Nome*</label>
              <input type="text" name="nome" id="nome_servico" class="form-control" placeholder="Nome do setor" required />
          </div>
          <div class="form-group">
              <label for="nome">Descrição</label>
              <textarea class="form-control" id="desc_servico" name="descricao"></textarea>
          </div>
          <div class="form-group">
              <label for="tipo">Status</label>
              <select name="status" class="form-control">
                  <option value="1">Ativo</option>
                  <option value="0">inativo</option>
              </select>
          </div>
          
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <input type="hidden" name="servico" id="servico" />
        <button type="submit" class="btn btn-primary">Salvar</button>
      </div>
    </form>
    </div>
  </div>
</div>

<!-- Modal Excluir Serviço-->
<div class="modal fade" id="delServico" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="<?=base_url('admin/servicos/excluir'); ?>" method="post" enctype="multipart/form-data">
      <div class="modal-header" style="background: #d9534f;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Excluir Setor</h4>
      </div>
      <div class="modal-body">
          <p>Deseja realmente EXCLUIR PERMANENTEMENTE o setor <b class="nome_servico"></b>?</p>
          <p>Após a confirmação de exclusão esta ação, não poderá ser desfeita.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <input type="hidden" name="servico" id="id_serv" />
        <button type="submit" class="btn btn-danger">Excluir permanentemente</button>
      </div>
    </form>
    </div>
  </div>
</div>
<script src="<?=base_url('layout/admin/js/jquery.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
    
    $('.edita_servico').on('click', function(){
        
        var id = $(this).attr('data-id');
        var nome = $('.nome_servico_'+id).val();
        var desc = $('.desc_servico_'+id).val();
        $('#servico').val(id);
        $('#nome_servico').val(nome);
        $('#desc_servico').val(desc);
    });
    
    
    $('.delServico').on('click', function(){
        var nome = $(this).attr('data-nome');
        var id = $(this).attr('data-id');
        
        $('.nome_servico').html(nome);
        $('#id_serv').val(id);
    });
    
    
    
});
</script>