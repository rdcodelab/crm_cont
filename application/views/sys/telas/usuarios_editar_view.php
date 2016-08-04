<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <h3><i class="fa fa-angle-right"></i> Usuários</h3>
        <div class="row mt">
            <div class="col-lg-12">
                <p>Atualização de cadastro de usuários. <?=$this->uri->segment(3); ?></p>
                
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
                <form action="<?=base_url('/usuarios/editar/'.$usuario->idclientes_usuarios); ?>" method="post"> 
                <div class="form-group">
                    <label for="nome">Nome*</label>
                    <input type="text" name="nome" class="form-control" placeholder="Nome do usuário" required value="<?=$usuario->nome; ?>" />
                </div>               
                <div class="form-group">
                    <label for="email">E-mail*</label>
                    <input type="email" name="email" class="form-control" placeholder="E-mail" required value="<?=$usuario->email?>" />
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
                    <?php
                        // verifica nível de acesso do usuário
                        if($this->session->userdata('tipo') == 1){                                                                                
                    ?>
                    <select name="tipo" class="form-control">
                        <option value="2" <?=($usuario->tipo_usuario == 2) ? 'selected' : ''; ?>>Funcionário</option>
                        <option value="1" <?=($usuario->tipo_usuario == 1) ? 'selected' : ''; ?>>Gestor</option>
                    </select>
                    <?php
                        }else{
                            echo '<input type="text" value="Funcionário" class="form-control" disabled />';
                        }
                    ?>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="1" <?=($usuario->status == 1) ? 'selected' : ''; ?>>Ativo</option>
                        <option value="0" <?=($usuario->status == 0) ? 'selected' : ''; ?>>inativo</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="hidden" name="usuario" value="<?=$usuario->idclientes_usuarios; ?>" />                    
                    <button type="submit" class="btn btn-info">Salvar</button>
                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#frmSenha">
                        Redefinir Senha
                    </button>
                    <a href="<?=base_url('/usuarios'); ?>" title="Voltar" class="label label-default">Voltar</a>
                </div>
                </form>       
            </div>
        </div>
    </section><!--/wrapper -->
</section><!--/MAIN CONTENT -->

<!-- Modal -->
<div class="modal fade" id="frmSenha" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="<?=base_url('/usuarios/redefine_senha'); ?>" method="post">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Redefinição de Senha</h4>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label>Informe a nova senha</label>
                <input type="password" name="senha" id="senha1" class="form-control" />
            </div>

            <div class="form-group">
                <label>Confirme a nova senha</label>
                <input type="password" name="senha" id="senha2" class="form-control" />
            </div>
            <div class="retorno_senha"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <input type="submit" class="btn btn-primary btnSalvar" value="Salvar senha" />
          <input type="hidden" name="usuario" value="<?=$usuario->idclientes_usuarios; ?>" /> 
          
          <?php
            foreach($tipo_arquivo_per as $per):                
                echo '<input type="hidden" name="tipo_arquivo[]" value="'.$per->id_tipo.'" />';
            endforeach;
          ?>
        </div>
        </form>
    </div>
  </div>
</div>


<script src="<?=base_url('/layout/admin/js/jquery.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){    
    
    // verifica se as senhas são iguais
    $('#senha2').on('change',function(){
        var senha1 = $('#senha1').val();
        var senha2 = $(this).val();
        
        if(senha1 === senha2){
            $('.retorno_senha').html('<div class="alert alert-success">Senha Ok!</div>');
            $('.btnSalvar').attr('enabled');
            $('.btnSalvar').removeAttr('disabled');
        }else{
            $('.retorno_senha').html('<div class="alert alert-danger">As senhas não coincidem.</div>');
            $('.btnSalvar').attr('disabled', 'on');
            $('.btnSalvar').removeAttr('enabled');
        }                
    });
    
    
});    
</script>