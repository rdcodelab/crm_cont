<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <h3><i class="fa fa-briefcase"></i> Clientes</h3>
        <div class="row mt">
            <div class="col-lg-12">
                <p>Cadasro de clientes no sistema.</p>
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
                ?>
                <form action="<?=base_url('admin/clientes/add'); ?>" method="post">
                                   
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cnpjcei">CNPJ/ CEI*</label>
                                <input type="text" name="cnpj" id="cnpjcei" class="form-control" placeholder="CNPJ ou CEI" required />                                            
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="categoria">Razão Social*</label>
                                <input type="text" name="razao" class="form-control" placeholder="Razão Social do Cliente" required />
                            </div>                                 
                        </div>
                    </div>                                


                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="responsavel">Responsável*</label>
                                <input type="text" name="responsavel" class="form-control" placeholder="Responsável/ Contato principal" required />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">E-mail*</label>
                                <input type="email" name="email" class="form-control" placeholder="nome@provedor.com" required />
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="telefone">Telefone</label>
                                <input type="text" name="telefone" class="form-control" placeholder="(99) 9999 9999" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="celular">Celular</label>
                                <input type="text" name="celular" class="form-control" placeholder="(99) 9999 9999" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="celularsms">Celular SMS</label>
                                <input type="text" name="celularsms" class="form-control celular" placeholder="(99) 9999 9999" />
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="categoria">Ramo de Atividade*</label>
                                <select class="form-control" name="slc_categoria" required>
                                    <option value="">Selecione um ramo</option>
                                    <?php
                                        foreach ($tipos_usuario as $tu):
                                            echo '<option value="'.$tu->id_tipo.'">'.$tu->nome_tipo.'</option>';
                                        endforeach;
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Acesso</label>
                                <select class="form-control" name="slc_status">
                                    <option value="1">Liberado</option>
                                    <option value="0">Bloqueado</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="servicos">Serviços contratados*</label>
                                <br/>
                                    <?php
                                        foreach ($servicos as $sr):
                                            echo '<div class="col-md-3">';
                                            echo '<input type="checkbox" name="servicos[]" value="'.$sr->id.'" id="servico-'.$sr->id.'" /> <label for="servico-'.$sr->id.'">'.$sr->nome.'</label> ';
                                            echo '</div>';
                                        endforeach;
                                    ?>

                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tipo_usuario">Tipo de Usuário</label>
                                <input type="text" value="Administrador" disabled class="form-control" />
                                <input type="hidden" value="1" name="tipo_usuario" />                                
                            </div>
                        </div><!--.col-md-3-->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nome_usuario">Nome*</label>
                                <input type="text" class="form-control" name="nome_usuario" placeholder="Nome do Usuário" required />
                            </div>
                        </div><!--.col-md-3-->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="email_usuario">E-mail*</label>
                                <input type="email" class="form-control" name="email_usuario" placeholder="nome@provedor.com" required />                                
                            </div>
                        </div><!--.col-md-3-->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="senha_usuario">Senha*</label>
                                <input type="password" class="form-control" name="senha_usuario" placeholder="Senha de Acesso" required />
                            </div>
                        </div><!--.col-md-3-->
                    </div><!--.row-->
                                                           
                    <?=anchor('/admin/clientes', 'Voltar', 'class="label label-default"'); ?>
                    <input type="submit" name="btnAddCliente" class="btn btn-primary" value="Cadastrar Cliente" />
                    <input type="checkbox" name="notificacao" checked > Enviar dados de acesso por e-mail.<br />
                    <small>*Campos de preenchimento obrigatório.</small>
                </form>
                
            </div><!--.col-lg-12-->
        </div><!--row-->
    </section><!--wrapper-->
</section><!--#main-content-->
<script src="<?php echo base_url('/layout/admin/js/jquery-1.8.3.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('/layout/admin/js/validaCPFCNPJ.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('/layout/admin/js/funcoes.js'); ?>" type="text/javascript"></script>
