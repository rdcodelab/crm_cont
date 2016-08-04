<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <h3><i class="fa fa-angle-right"></i> Usuários</h3>
        <div class="row mt">
            <div class="col-lg-12">
                <p>Atualização de cadastro de usuários.</p>
                
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
                <form action="<?=base_url('/admin/usuarios/editar/'.$usuario->id); ?>" method="post"> 
                <div class="form-group">
                    <label for="nome">Nome*</label>
                    <input type="text" name="nome" class="form-control" placeholder="Nome do usuário" required value="<?=$usuario->nome; ?>" />
                </div>               
                <div class="form-group">
                    <label for="email">E-mail*</label>
                    <input type="email" name="email" class="form-control" placeholder="E-mail" required value="<?=$usuario->email?>" />
                </div>
                <div class="form-group">
                    <label for="senha">Permissões de acesso</label>                      
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>                          
                                <th style="width: 20px"></th>                                                         
                                <th>Módulo</th>                                                         
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                foreach($modulos as $mod): 
                                    $permissao = '';
                                    if(count($mod_user) > 0):
                                        foreach($mod_user as $mu):
                                            if($mu->modulos_id == $mod->id):
                                                $permissao = 'checked';
                                            endif;
                                        endforeach;
                                    endif;
                            ?>
                            <tr for="item_<?=$mod->id; ?>">
                                <td><input type="checkbox" name="perm_acesso[]" id="item_<?=$mod->id; ?>" value="<?=$mod->id; ?>" <?=$permissao; ?> <?=($this->session->userdata('tipo') == 2) ? 'disabled' : ''; ?> /></td>
                                <td><?php echo $mod->nome; ?></td>                                                                
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="form-group">
                    <label for="tipo">Tipo de usuário</label>
                    <select name="tipo" class="form-control" id="tipo" <?=($this->session->userdata('tipo') == 2) ? 'disabled' : ''; ?> >
                        <option value="2" <?=($usuario->tipo_usuario == 2) ? 'selected' : ''; ?>>Usuário</option>
                        <option value="1" <?=($usuario->tipo_usuario == 1) ? 'selected' : ''; ?>>Administrador</option>
                    </select>
                </div>
                    
                                    
                <div class="form-group">
                    <label for="servico">Setor responsável*</label>
                    
                        <?php                            
                            if($usuario->tipo_usuario == 1){                                
                                echo '<div class="tipo_servico">';
                                echo '<input type="text" class="form-control" value="Todos os setores" disabled /><input type="hidden" name="servico" value="NULL" />';
                                echo '</div>';
                                
                                echo '<select name="servico" class="form-control" id="servico" style="display:none">';
                                echo '<option value="">Selecione qual setor o usuário será responsável</option>';
                                    foreach($servicos as $srv):
                                        // verifica qual o servico o usuário pertence
                                        if($usuario->id_servico === $srv->id){
                                            $selectServico = 'selected';
                                        }else{
                                            $selectServico = '';
                                        }
                                        echo '<option value="'.$srv->id.'" '.$selectServico.'>'.$srv->nome.'</option>';
                                    endforeach;                                
                                echo '</select>';
                                
                            }else{                                
                        ?>
                            <div class="tipo_servico"></div>
                            <select name="servico" class="form-control" id="servico">
                                <option value="">Selecione qual setor o usuário será responsável</option>
                                <?php
                                    
                                    foreach($servicos as $srv):
                                        // verifica qual o servico o usuário pertence
                                        if($usuario->id_servico === $srv->id){
                                            $selectServico = 'selected';
                                        }else{
                                            $selectServico = '';
                                        }
                                        echo '<option value="'.$srv->id.'" '.$selectServico.'>'.$srv->nome.'</option>';
                                    endforeach;
                                ?>
                            </select>   
                        <?php } ?>
                    
                                       
                </div>
                    
                    
                    
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="1" <?=($usuario->status == 1) ? 'selected' : ''; ?>>Ativo</option>
                        <option value="0" <?=($usuario->status == 0) ? 'selected' : ''; ?>>inativo</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="hidden" name="usuario" value="<?=$usuario->id; ?>" />                    
                    <button type="submit" class="btn btn-info">Salvar</button>
                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#frmSenha">
                        Redefinir Senha
                    </button>
                    <a href="<?=base_url('/admin/usuarios'); ?>" title="Voltar" class="label label-default">Voltar</a>
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
        <form action="<?=base_url('/admin/usuarios/redefine_senha'); ?>" method="post">
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
          <input type="hidden" name="usuario" value="<?=$usuario->id; ?>" />          
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
    
    // verifica se o usuario será funcionario ou gestor
    $('#tipo').on('change', function(){
        var tipo = $(this).val();
        if(tipo == 1){
            $('#servico').css('display', 'none');
            $('#servico').attr('disabled');
            $('#servico').attr('name', 'servico2');
            $('.tipo_servico').html('<input type="text" class="form-control" value="Todos os setores" disabled /><input type="hidden" name="servico" value="0" />').css('display', 'block');
        }else if(tipo == 2){
            $('#servico').css('display', 'block');
            $('#servico').removeAttr('disabled');
            $('#servico').attr('name', 'servico');
            $('.tipo_servico').html('').css('display', 'none');
        }        
    });
    
    
});    
</script>