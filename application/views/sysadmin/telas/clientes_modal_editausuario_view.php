<script type="text/javascript">
$('#upUsuario').modal('show');
</script>
<div class="modal fade" id="upUsuario" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="<?=base_url('/admin/clientes/edita_usuario'); ?>" method="post" enctype="multipart/form-data">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Cadastro de Usuários</h4>
      </div>
      <div class="modal-body">
          <div class="form-group">
              <label for="nome">Nome*</label>
              <input type="text" name="nome_usuario" class="form-control" placeholder="Nome do usuário" value="<?=$user->nome; ?>" required />
          </div>
          <div class="form-group">
              <label for="email">E-mail*</label>
              <input type="email" name="email_usuario" class="form-control" placeholder="E-mail" value="<?=$user->email; ?>" required />
          </div>
          <div class="form-group">
              <label for="senha">Senha*</label>
              <input type="password" name="senha_usuario" class="form-control" placeholder="Senha de acesso" />
          </div>
          <div class="form-group">
              <label for="permissao">Permissão de acesso a arquivos</label>
              <ul>
                <?php 
                    foreach($tipo_arquivo as $tpo_arq): 
                        $selectTipo = '';
                        foreach($tipo_arquivo_per as $per):
                            if($tpo_arq->id_tipo == $per->id_tipo){
                                $selectTipo = 'checked';
                            }
                        endforeach;
                ?>
                  <li for="tipo<?=$tpo_arq->id_tipo?>"><input type="checkbox" id="tipo<?=$tpo_arq->id_tipo; ?>" name="tipo_arquivo[]" value="<?=$tpo_arq->id_tipo; ?>" <?=$selectTipo; ?> /> <?=$tpo_arq->nome; ?> > <?=$tpo_arq->nome_tipo; ?></li>
                <?php endforeach; ?>
              </ul>
          </div>
          <div class="form-group">
              <label for="tipo">Tipo de usuário</label>
              <select name="tipo_usuario" class="form-control">
                  <option value="2" <?=($user->tipo_usuario == 2) ? 'selected' : ''; ?>>Funcionário</option>
                  <option value="1" <?=($user->tipo_usuario == 1) ? 'selected' : ''; ?>>Gestor</option>
              </select>
          </div>
          <div class="form-group">
              <label for="tipo">Status</label>
              <select name="status_usuario" class="form-control">
                  <option value="1"  <?=($user->status == 1) ? 'selected' : ''; ?>>Ativo</option>
                  <option value="0"  <?=($user->status == 0) ? 'selected' : ''; ?>>Inativo</option>
              </select>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary">Atualizar</button>
        <input type="hidden" name="usuario" value="<?=$user->idclientes_usuarios; ?>" />
        <input type="hidden" name="cliente" value="<?=$user->id_clientes; ?>" />
        <input type="hidden" name="rota" value="1" />
      </div>
    </form>
    </div>
  </div>
</div>