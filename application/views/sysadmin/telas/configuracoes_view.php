<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <h3><i class="fa fa-cogs"></i> Configurações</h3>
        <div class="row mt">
            <div class="col-lg-12">
                <p>Configurações do sistema.</p>
                
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
                <form action="<?=base_url('/admin/configuracoes/editar/'); ?>" method="post"> 
                <div class="form-group">
                    <label>Logo</label>
                    <br />
                    <?php 
                        if(empty($config->logo_escritorio)){
                            echo "<pre>Logo não cadastrada</pre>";
                            $nomeBt = "Cadastrar logo";
                        }else{                            
                            echo '<img src="'.base_url('/uploads/imagens/'.$config->logo_escritorio).'" style="max-width: 200px" class="img-rounded" /> <br />';
                            $nomeBt = "Alterar logo";
                        }    
                    ?>
                    <br />
                     <!-- Button trigger modal -->                                        
                     <a href="javascript:void()" title="<?php echo $nomeBt; ?>" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#formImg"><?php echo $nomeBt; ?></a>                     
                </div>
                    
                <div class="form-group">
                    <label for="razao">Razão Social*</label>
                    <input type="text" id="razao" name="razao" class="form-control" placeholder="Razão Social" required value="<?=$config->razao_social_escritorio; ?>" />
                </div>
                <div class="form-group">
                    <label for="fantasia">Nome Fantasia (Nome de exibição no sistema)*</label>
                    <input type="text" id="fantasia" name="fantasia" class="form-control" placeholder="Nome Fantasia" required value="<?=$config->nome_fantasia_escritorio; ?>" />
                </div>
                <div class="form-group">
                    <label for="cnpj">CNPJ</label>
                    <input type="text" id="cnpj" name="cnpj" class="form-control" placeholder="99.999.999/9999-99" value="<?=$config->cnpj_escritorio; ?>" />
                </div>
                <div class="form-group">
                    <label for="endereco">Endereço</label>
                    <input type="text" id="endereco" name="endereco" class="form-control" placeholder="Endereço" value="<?=$config->endereco_escritorio; ?>" />
                </div>
                <div class="form-group">
                    <label for="bairro">Bairro</label>
                    <input type="text" id="bairro" name="bairro" class="form-control" placeholder="Bairro" value="<?=$config->bairro_escritorio; ?>" />
                </div>
                <div class="form-group">
                    <label for="cep">CEP</label>
                    <input type="text" id="cep" name="cep" class="form-control" placeholder="99.999-999" value="<?=$config->cep_escritorio; ?>" />
                </div>
                <div class="form-group">
                    <label for="fone">Telefone</label>
                    <input type="text" id="fone" name="fone" class="form-control" placeholder="(99) 3333 3333/ 9999 9999" value="<?=$config->telefone_escritorio; ?>" />
                </div>
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="contato@seudominio.com.br" value="<?=$config->email_escritorio; ?>" />
                </div>
                <div class="form-group">
                    <label for="site">Site</label>
                    <input type="text" id="site" name="site" class="form-control" placeholder="www.seudominio.com.br" value="<?=$config->site_escritorio; ?>" />
                </div>
                <div class="form-group">
                    <label for="vcto">Vencimento de arquivos (dias em que o arquivo ficará disponível, após o vencimento do mesmo)</label>
                    <input type="number" id="vcto" name="vcto" class="form-control" placeholder="Informe o número de dias" value="<?=$config->vcto_arquivos; ?>" />
                </div>
                
                
                <div class="form-group">                    
                    <button type="submit" class="btn btn-info">Salvar</button>                    
                </div>
                </form>       
            </div>
        </div>
    </section><!--/wrapper -->
</section><!--/MAIN CONTENT -->

<!-- Modal -->
<div class="modal fade" id="formImg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<?php echo form_open_multipart('admin/configuracoes/atualizaImg'); ?>    
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"><?php echo $nomeBt; ?></h4>
      </div>
      <div class="modal-body">
        <?php
            echo '<span class="validacao"> '.  validation_errors().' </span>';
            
            echo form_fieldset();
            
            echo form_label("Logo", "userfile");
            echo '<input type="file" name="userfile" id="userfile" class="form-control" />';
            
            echo form_fieldset_close();                       
        ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>        
        <?php 
            $atrBt = array("name"=>"btn", "value"=>"Salvar imagem", "class"=>"btn btn-default");
            echo form_submit($atrBt);
        ?>
        <input type="hidden" name="imgAntiga" value="<?php echo $config->logo_escritorio; ?>" />        
      </div>
    </div>
  </div>
<?php echo form_close();?>    
</div>
