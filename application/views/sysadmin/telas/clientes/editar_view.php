<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <h3><i class="fa fa-briefcase"></i> Clientes</h3>
        <div class="row mt">
            <div class="col-lg-12">
                <p>Atualização de cliente no sistema.</p>
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
                    
                    // verifica qual tab ativar
                    if($this->uri->segment(5) == 'funcionarios'){
                        $tabAtivaF = 'class="active"';
                        $boxAticoF = 'active';
                        $tabAtivaC = '';
                        $boxAticoC = '';
                    }else{
                        $tabAtivaC = 'class="active"';
                        $boxAticoC = 'active';
                        $tabAtivaF = '';
                        $boxAticoF = '';
                    }
                    echo validation_errors();
                    
                    if(count($cliente) == 0){
                        echo '<div class="alert alert-warning">Cliente não localizado, <a href="'.base_url('/admin/clientes').'" title="Voltar para lista de clientes">clique aqui</a> para voltar</div>';
                    }else{
                ?>
               
                
                    <div>
                        
                            <form action="<?=base_url('admin/clientes/editar/'.$cliente->id_clientes); ?>" method="post">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="cnpjcei">CNPJ/ CEI*</label>
                                            <input type="text" name="cnpjcei" class="form-control" placeholder="CNPJ ou CEI" value="<?=$cliente->cnpj; ?>" required />
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="categoria">Razão Social*</label>
                                            <input type="text" name="razao" class="form-control" placeholder="Razão Social do Cliente" required value="<?=$cliente->razao_social; ?>"/>
                                        </div>
                                    </div>
                                </div>                                
                                                                                                                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="responsavel">Responsável*</label>
                                            <input type="text" name="responsavel" class="form-control" placeholder="Responsável/ Contato principal" value="<?=$cliente->responsavel; ?>" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">E-mail*</label>
                                            <input type="email" name="email" class="form-control" placeholder="nome@provedor.com" required value="<?=$cliente->email; ?>" />
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="telefone">Telefone</label>
                                            <input type="text" name="telefone" class="form-control" placeholder="(99) 9999 9999" value="<?=$cliente->telefone; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="celular">Celular</label>
                                            <input type="text" name="celular" class="form-control" placeholder="(99) 9999 9999" value="<?=$cliente->celular; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="celularsms">Celular SMS</label>
                                            <input type="text" name="celularsms" class="form-control celular" placeholder="(99) 9999 9999" value="<?=$cliente->celular_sms; ?>" />
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="categoria">Ramo de atividade*</label>
                                            <select class="form-control" name="slc_categoria" required>
                                                <option value="">Selecione um ramo de atividade</option>
                                                <?php
                                                    foreach ($tipos_usuario as $tu):
                                                        if($cliente->id_tipo == $tu->id_tipo){
                                                            $select = 'selected';
                                                        }else{
                                                            $select = '';
                                                        }
                                                        echo '<option value="'.$tu->id_tipo.'" '.$select.'>'.$tu->nome_tipo.'</option>';
                                                    endforeach;
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status">Acesso</label>
                                            <select class="form-control" name="slc_status">
                                                <option value="1" <?=($cliente->status == 1) ? 'selected' : ''; ?>>Liberado</option>
                                                <option value="0" <?=($cliente->status == 0) ? 'selected' : ''; ?>>Bloqueado</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                </div>
                                    
                                    <div class="form-group">
                                        <label for="servicos">Serviços contratados</label>
                                        <br/>
                                            <?php
                                                foreach ($servicos as $sr):
                                                    $check_servico = '';
                                                    // verifica qual serviço o cliente já esta vinculado
                                                    if(count($servicos_clientes) > 0):                                                       
                                                        foreach($servicos_clientes as $scl):
                                                            if($scl->id_clientes == $cliente->id_clientes && $sr->id == $scl->id_servicos):
                                                                $check_servico = 'checked';
                                                            endif;
                                                        endforeach;
                                                    endif;
                                                    
                                                    echo '<div class="col-md-3">';
                                                    echo '<input type="checkbox" name="servicos[]" value="'.$sr->id.'" '.$check_servico.' id="servico-'.$sr->id.'" /> <label for="servico-'.$sr->id.'">'.$sr->nome.'</label>';
                                                    echo '</div>';
                                                endforeach;
                                            ?>

                                    </div>
                                    <br />
                                    <div class="form-group">
                                        <div class="text-left">
                                            <input type="hidden" name="cliente" value="<?=$cliente->id_clientes; ?>" />
                                            <input type="submit" name="btnAddCliente" class="btn btn-primary" value="Salvar Dados" />
                                        </div>
                                        <div class="text-right">
                                            <a href="javascript:void()" title="Excluir cliente" class="btn btn-danger delCliente" data-toggle="modal" data-nome="<?=$cliente->razao_social; ?>" data-id="<?=$cliente->id_clientes; ?>" data-target="#delCliente"><i class="fa fa-trash-o"></i> Excluir cadastro</a>
                                        </div>
                                    </div>    
                                    
                                 </form>

                        
                    </div>
                    <?=anchor('/admin/clientes', 'Voltar', 'class="label label-default"'); ?>
                   
                <?php } ?>
                
                
            </div><!--.col-lg-12-->
        </div><!--row-->
    </section><!--wrapper-->
</section><!--#main-content-->


<!-- Modal Excluir Usuario-->
<div class="modal fade" id="delCliente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="<?=base_url('admin/clientes/excluir'); ?>" method="post" enctype="multipart/form-data">
      <div class="modal-header" style="background: #d9534f;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Exclusão de Clientes</h4>
      </div>
      <div class="modal-body">
          <p>Deseja realmente EXCLUIR PERMANENTEMENTE o cliente <b class="nome_cliente"><?=$cliente->razao_social; ?></b>?</p>
          <p>Ao excluir este cliente, automaticamente serão deletados todas as informações vinculadas a ele no sistema, tais como: arquivos, logs de acessos, chamados e protocolos.</p>
          <p>Após a confirmação de exclusão esta ação, não poderá ser desfeita.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <input type="hidden" name="cliente" id="id_cliente" value="<?=$cliente->id_clientes; ?>" />
        <button type="submit" class="btn btn-danger">Excluir permanentemente</button>
      </div>
    </form>
    </div>
  </div>
</div>