<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <h3><i class="fa fa-users"></i> Usuários</h3>
        <div class="row mt">
            <div class="col-lg-12">
                <p> Usuários cadastrados no sistema.</p>
                <p>                    
                    <a href="javascript:void()" title="Cadastrar Usuário" class="btn btn-info" data-toggle="modal" data-target="#addUsuario">Cadastrar usuário</a>
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
                                    '.$this->session->flashdata('danger').'
                                </div>';
                    }
                ?>
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Usuário</th>
                            <th>E-mail</th>                            
                            <th>Perfil</th>                            
                            <th>Nível de permissão</th>                            
                            <th>Opções</th>
                        </tr>
                    </thead>                    
                    <tbody>
                        <?php 
                            foreach($usuarios as $us): 
                                switch($us->tipo_usuario){
                                    case 1:
                                        $tipo = 'Gestor';
                                    break;
                                    case 2:
                                        $tipo = 'Funcionário';
                                    break;
                                }

                                // verifica permissões do usuário
                                $permissao = $this->clientes->lista_tipoArquivo_usuario($us->idclientes_usuarios);
                                $nivel = '';
                                if(count($permissao) > 0){
                                    foreach($permissao as $perm):
                                        $nivel .= $perm->nome.' > '.$perm->nome_tipo.'<br />';
                                    endforeach;
                                }
                        ?>
                        <tr>
                            <td><?=$us->nome; ?></td>
                            <td><?=$us->email; ?></td>
                            <td><?=$tipo; ?></td>
                            <td><?=$nivel; ?></td>
                            <td>
                                <a href="<?=base_url('/usuarios/editar/'.$us->idclientes_usuarios); ?>" title="Editar dados" class="btn btn-info"><i class="fa fa-edit"></i></a>
                                <a href="javascript:void()" title="Editar dados" class="btn btn-danger delUsuario" data-toggle="modal" data-nome="<?=$us->nome; ?>" data-id="<?=$us->idclientes_usuarios; ?>" data-target="#delUsuario"><i class="fa fa-trash-o"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            
            </div>
        </div>
    </section><!--/wrapper -->
</section><!--/MAIN CONTENT -->

<!-- Modal -->
<div class="modal fade" id="addUsuario" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="<?=base_url('/usuarios/add'); ?>" method="post" enctype="multipart/form-data">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Cadastro de Usuários</h4>
      </div>
      <div class="modal-body">
          <div class="form-group">
              <label for="nome">Nome*</label>
              <input type="text" name="nome" class="form-control" placeholder="Nome do usuário" required />
          </div>          
          <div class="form-group">
              <label for="email">E-mail*</label>
              <input type="email" name="email" class="form-control" placeholder="E-mail" required />
          </div>
          <div class="form-group">
              <label for="senha">Senha*</label>
              <input type="password" name="senha" class="form-control" placeholder="Senha de acesso" required />
          </div>
          <div class="form-group">
              <label for="permissao">Permissão de acesso a arquivos</label>
              <ul>
                  <?php foreach($tipo_arquivo as $tpo_arq): ?>
                  <li for="tipo<?=$tpo_arq->id_tipo?>"><input type="checkbox" id="tipo<?=$tpo_arq->id_tipo; ?>" name="tipo_arquivo[]" checked="true" value="<?=$tpo_arq->id_tipo; ?>" /> <?=$tpo_arq->nome; ?> > <?=$tpo_arq->nome_tipo; ?></li>
                  <?php endforeach; ?>
              </ul>
          </div>
          <div class="form-group">
              <label for="tipo">Tipo de usuário</label>
              <select class="form-control" disabled>
                  <option value="2">Funcionário</option>                  
              </select>
              <input type="hidden" name="tipo" value="2" />
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
<!-- Modal Excluir Usuario-->
<div class="modal fade" id="delUsuario" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="<?=base_url('/usuarios/excluir'); ?>" method="post" enctype="multipart/form-data">
      <div class="modal-header" style="background: #d9534f;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Cadastro de Usuários</h4>
      </div>
      <div class="modal-body">
          <p>Deseja realmente EXCLUIR PERMANENTEMENTE o usuário <b class="nome_usuario"></b>?</p>
          <p>Após a confirmação de exclusão esta ação, não poderá ser desfeita.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <input type="hidden" name="usuario" id="id_user" />
        <button type="submit" class="btn btn-danger">Excluir permanentemente</button>
      </div>
    </form>
    </div>
  </div>
</div>
<script src="<?=base_url('layout/admin/js/jquery.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
    
    $('.delUsuario').on('click', function(){
        var nome = $(this).attr('data-nome');
        var id = $(this).attr('data-id');
        
        $('.nome_usuario').html(nome);
        $('#id_user').val(id);
    });
    
});
</script>