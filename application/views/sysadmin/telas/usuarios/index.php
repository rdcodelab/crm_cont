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
                            <th>Serviços</th>
                            <th>Permissões de Acesso</th>
                            <th>Opções</th>
                        </tr>
                    </thead>                    
                    <tbody>
                        <?php 
                            foreach($usuarios as $us): 
                                // define perfil do usuário
                                switch($us->tipo_usuario){
                                    case 1:
                                        $perfil = "Administrador";
                                        $n_servico = "Todos";
                                    break;
                                    case 2:
                                        $perfil = "Usuário";
                                        // verifica qual servico vinculado
                                        foreach($servicos as $lsr):
                                            if($us->id_servico == $lsr->id):
                                                $n_servico = $lsr->nome;
                                            endif;
                                        endforeach;
                                    break;
                                }                                
                        ?>
                        <tr>
                            <td><?=$us->nome; ?></td>
                            <td><?=$us->email; ?></td>
                            <td><?=$perfil; ?></td>
                            <td><?=$n_servico; ?></td>
                            <td>
                                <?php
                                    $modulos_cadastrados = "";
                                    foreach($mod_user as $mu):
                                        if($mu->usuarios_id == $us->id):
                                            
                                            foreach($modulos as $md):                                                                                               
                                                if($md->id == $mu->modulos_id):  
                                                    $modulos_cadastrados .= $md->nome.', ';                                                                                                           
                                                endif;                                                 
                                            endforeach;                                                                                       
                                        endif;
                                    endforeach;
                                    echo substr($modulos_cadastrados, 0, -2);
                                ?>
                            </td>
                            <td>
                                <a href="<?=base_url('/admin/usuarios/protocolos/'.$us->id); ?>" data-toggle="tooltip" title="Logs e protocolos" class="btn btn-default"><i class="fa fa-list-alt"></i></a>
                                <a href="<?=base_url('/admin/usuarios/editar/'.$us->id); ?>" data-toggle="tooltip" title="Editar dados" class="btn btn-info"><i class="fa fa-edit"></i></a>
                                <a href="javascript:void()" title="Editar dados" class="btn btn-danger delUsuario" data-toggle="modal" data-nome="<?=$us->nome; ?>" data-id="<?=$us->id; ?>" data-target="#delUsuario"><i class="fa fa-trash-o"></i></a>
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
        <form action="<?=base_url('/admin/usuarios/add'); ?>" method="post" enctype="multipart/form-data">
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
              <label for="senha">Permissões de acesso</label>
              <table class="table table-hover table-striped">
                  <thead>
                      <tr>                          
                          <th style="width: 20px;"></th>                                                  
                          <th>Módulo</th>                                                  
                      </tr>
                  </thead>
                  <tbody>
                      <?php foreach($modulos as $mod): ?>
                      <tr>
                          <td><input type="checkbox" name="perm_acesso[]" value="<?=$mod->id; ?>" /></td>
                          <td><?php echo $mod->nome; ?></td>                                                    
                      </tr>
                      <?php endforeach; ?>
                  </tbody>
              </table>
          </div>          
          <div class="form-group">
              <label for="tipo">Tipo de usuário</label>
              <select name="tipo" class="form-control" id="tipo">
                  <option value="2">Usuário</option>
                  <option value="1">Administrador</option>
              </select>
          </div>
          <div class="form-group">
              <label for="servico">Setor responsável*</label>
                <select name="servico" class="form-control" id="servico" required>
                    <option value="">Selecione qual setor o usuário será responsável</option>
                    <?php
                        foreach($servicos as $srv):
                            echo '<option value="'.$srv->id.'">'.$srv->nome.'</option>';
                        endforeach;
                    ?>
                </select>
              <div class="tipo_servico">                
              </div>
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
        <form action="<?=base_url('/admin/usuarios/excluir'); ?>" method="post" enctype="multipart/form-data">
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
    // verifica se o usuario será funcionario ou gestor
    $('#tipo').on('change', function(){
        var tipo = $(this).val();
        if(tipo == 1){
            $('#servico').css('display', 'none').removeAttr('required');
            $('.tipo_servico').html('<input type="text" class="form-control" value="Todos os setores" disabled /><input type="hidden" name="servico" value="NULL" />').css('display', 'block');
        }else if(tipo == 2){
            $('#servico').css('display', 'block');
            $('.tipo_servico').html('').css('display', 'none');
        }        
    });
    
    $('.delUsuario').on('click', function(){
        var nome = $(this).attr('data-nome');
        var id = $(this).attr('data-id');
        
        $('.nome_usuario').html(nome);
        $('#id_user').val(id);
    });
    
});
</script>