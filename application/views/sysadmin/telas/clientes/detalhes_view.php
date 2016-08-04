<!--main content start-->
<section id="main-content">
    
    <section class="wrapper site-min-height">
        <h3><i class="fa fa-folder-open"></i>Pasta do Cliente</h3>
        <div class="row mt">
            <div class="col-lg-12">                
                
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
                    
                    if(count($cliente) == 0){
                        echo '<div class="alert alert-warning">Cliente não localizado, <a href="'.base_url('/admin/clientes').'" title="Voltar para lista de clientes">clique aqui</a> para voltar</div>';
                    }else{
                ?>
                
                <div class="white-panel pn">
                    <div class="white-header">
                        Dados do cliente
                    </div>
                    
                    <div class="margin10">
                    
                        <div class="row linha">
                            <div class="col-lg-1 text-left"><b>Razão Social</b></div>
                            <div class="col-lg-5 text-left"><?=$cliente->razao_social; ?></div>                            
                        </div>
                        <div class="row linha">
                            <div class="col-lg-1 text-left"><b>CNPJ/ CEI</b></div>
                            <div class="col-lg-5 text-left"><?=$cliente->cnpj; ?></div>                            
                        </div>                        
                        <div class="row linha">
                            <div class="col-lg-1 text-left"><b>Telefone</b></div>
                            <div class="col-lg-3 text-left"><?=$cliente->telefone; ?></div>
                            
                            <div class="col-lg-1 text-left"><b>Celular</b></div>
                            <div class="col-lg-3 text-left"><?=($cliente->celular != "") ? $cliente->celular : ''; ?></div>
                            
                            <div class="col-lg-1 text-left"><b>Celular SMS</b></div>
                            <div class="col-lg-3 text-left"><?=($cliente->celular_sms != "") ? $cliente->celular_sms : ''; ?></div>
                            
                        </div>
                        <div class="row linha">
                            <div class="col-lg-1 text-left"><b>Responsável</b></div>
                            <div class="col-lg-5 text-left"><?=$cliente->responsavel; ?></div>                        
                            <div class="col-lg-1 text-left"><b>E-mail</b></div>
                            <div class="col-lg-5 text-left"><?=$cliente->email; ?></div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-12 margin10 text-right" style="width: 99%">
                                <?=anchor('admin/clientes/editar/'.$cliente->id_clientes, 'Editar dados', 'class="btn btn-default" target="_blank"'); ?>
                            </div>
                        </div>
                        
                    </div><!--.margin10-->
                </div>
                
                <div>
                    <div class="margin10">
                        <a href="<?=base_url('/admin/clientes'); ?>" title="Voltar" class="btn btn-default">Voltar</a>
                        <a href="javascript:void()" title="Cadastrar Arquivo" class="btn btn-info" data-toggle="modal" data-target="#addArquivo">Cadastrar documento</a>
                        <a href="javascript:void()" title="Cadastrar Usuário" class="btn btn-info" data-toggle="modal" data-target="#addUsuario">Cadastrar Funcionário</a>
                    </div>
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                      <li role="presentation" class="active"><a href="#arquivosrecebidos" aria-controls="arquivosrecebidos" role="tab" data-toggle="tab">Documentos recebidos <span class="badge"><?=count($arquivos_recebidos); ?></span></a></li>                      
                      <li role="presentation"><a href="#arquivosenviados" aria-controls="arquivosenviados" role="tab" data-toggle="tab">Documentos não Abertos <span class="badge"><?=count($arquivos_enviados); ?></span></a></li>                      
                      <li role="presentation"><a href="#chamados" aria-controls="chamados" role="tab" data-toggle="tab">Chamados <span class="badge"><?=count($chamados); ?></span></a></li>                      
                      <li role="presentation"><a href="#usuarios" aria-controls="usuarios" role="tab" data-toggle="tab">Usuários <span class="badge"><?=count($usuarios); ?></span></a></li>
                      <li role="presentation"><a href="#logs" aria-controls="logs" role="tab" data-toggle="tab">Logs e Protocolos <span class="badge"><?=count($protocolos); ?></span></a></li>                      
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <!-- ARQUIVOS ENVIADOS -->
                        <div role="tabpanel" class="tab-pane" id="arquivosenviados" style="padding: 10px;">
                            <?php
                                if(count($arquivos_enviados) == 0){
                                    echo '<pre>Não há arquivos não abertos no momento, para ver todos os arquivos <a href="'.base_url('/admin/arquivos').'" title="Ver todos os arquivos">clique aqui</a>.</pre>';
                                }else{
                            ?>
                                        <table class="table table-hover table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Arquivo</th>                                                    
                                                    <th>Tipo de Arquivo</th>
                                                    <th>Enviado por</th>
                                                    <th>Cadastro Documento</th>
                                                    <th>Vencimento Documento</th>
                                                    <th>Status</th>
                                                    <th>Opções</th>
                                                </tr>
                                            </thead>                    
                                            <tbody>
                                                <?php 
                                                    foreach($arquivos_enviados as $ln): 
                                                        
                                                        /********************************************** 
                                                         * pega dados funcionário responsável
                                                         *********************************************/                                                         
                                                        $resp_arquivo = $this->usuarios->lista_usuario_id($ln->id_usuario);
                                                ?>
                                                <tr>
                                                    <td><?=anchor('uploads/arquivos/'.$ln->arquivo, $ln->titulo, 'target="_blank"'); ?></td>                                                    
                                                    <td>
                                                        <?php

                                                            foreach($tipos as $tp):
                                                                if($tp->id_tipo === $ln->id_tipo):
                                                                    echo $tp->nome_tipo;
                                                                endif;
                                                            endforeach;

                                                        ?>
                                                    </td>
                                                    <td><?=$resp_arquivo->nome; ?></td>
                                                    <td><?=dataHora_BR($ln->data_cadastro); ?></td>                            
                                                    <td><?=dataBR($ln->data_vencimento); ?></td>                            
                                                    <td>
                                                        <?php                                    
                                                            switch($ln->status){
                                                                // arquivo pendente
                                                                case 0 :
                                                                    echo '<span class="label label-default">Não aberto</span>';
                                                                break;
                                                                // arquivo visualizado
                                                                case 1 :
                                                                    echo '<span class="label label-info">Visualizado</span>';
                                                                break;
                                                                // arquivo vencido
                                                                case 2 :
                                                                    echo '<span class="label label-warning">Vencido</span>';
                                                                break;
                                                                // arquivo excluido (lixeira)
                                                                case 3 :
                                                                    echo '<span class="label label-danger">Excluído</span>';
                                                                break;
                                                            }
                                                        ?>
                                                    </td>                            
                                                    <td> 
                                                        <a href="javascript:void()" title="Excluir arquivo" class="btn btn-danger btn-sm delArquivo" data-toggle="modal" data-nome="<?=$ln->titulo; ?>" data-id="<?=$ln->idarquivos; ?>" data-target="#delArquivo"><i class="fa fa-trash-o"></i></a>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                            <?php } ?>
                        </div><!--#arquivosenviados-->
                        
                        <!-- ARQUIVOS ENVIADOS -->
                        <div role="tabpanel" class="tab-pane active" id="arquivosrecebidos" style="padding: 10px;">
                            <?php
                                if(count($arquivos_recebidos) == 0){
                                    echo '<pre>Este cliente ainda não enviou nenhum arquivo.</pre>';
                                }else{
                            ?>
                                        <table class="table table-hover table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Arquivo</th>                                                    
                                                    <th>Tipo de Arquivo</th>
                                                    <th>Enviado por</th>
                                                    <th>Cadastro Documento</th>                                                                                                        
                                                    <th>Opções</th>
                                                </tr>
                                            </thead>                   
                                            <tbody>
                                                <?php 
                                                    foreach($arquivos_recebidos as $lnr): 
                                                        
                                                        /********************************************** 
                                                         * pega dados funcionário responsável
                                                         *********************************************/                                                                                                                  
                                                        $tipo = $this->arquivos->lista_tipo_id($lnr->id_tipo);
                                                        $servico = $this->servicos->lista_servico_id($lnr->id_servicos);                                
                                                        $resp_arquivo = $this->clientes->lista_cliente_usuario_id($lnr->id_usuario);
                                                ?>
                                                <tr>
                                                    <td><?=anchor('uploads/arquivos/'.$lnr->arquivo, $lnr->titulo, 'target="_blank"'); ?></td>                                                    
                                                    <td><?=$servico->nome." > ".$tipo->nome_tipo; ?></td>
                                                    <td><?=$resp_arquivo->nome; ?></td>
                                                    <td><?=dataHora_BR($lnr->data_cadastro); ?></td>                                                                                                     
                                                    <td> 
                                                        <a href="javascript:void()" title="Excluir arquivo" class="btn btn-danger btn-sm delArquivo" data-toggle="modal" data-nome="<?=$lnr->titulo; ?>" data-id="<?=$lnr->idarquivos; ?>" data-target="#delArquivo"><i class="fa fa-trash-o"></i></a>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                            <?php } ?>
                        </div><!--#arquivosrecebidos-->
                        
                        <!-- CHAMADOS-->
                        <div role="tabpanel" class="tab-pane" id="chamados" style="padding: 10px;">
                          
                            <?php
                                if(count($chamados) == 0){
                                    echo '<pre>Você não abriu nenhum chamado até o momento.</pre>';
                                }else{
                            ?>
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Data Abertura</th>
                                        <th>Número</th>                                        
                                        <th>Assunto</th>                            
                                        <th>Urgência</th>                            
                                        <th>Enviado por</th>                            
                                        <th>Atendido por</th>                            
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
                                            // pega nome do usuário que enviou
                                            foreach($usuarios as $us){
                                                if($us->idclientes_usuarios == $ln->idclientes_usuarios){
                                                    $n_usuario = $us->nome;
                                                }
                                            }
                                            // pega nome do funcionarios que esta realizando atendimento
                                            $n_funcionario = "";
                                            foreach($funcionarios as $fun):
                                                if($fun->id == $ln->id_usuarios){
                                                    $n_funcionario = $fun->nome;
                                                }
                                            endforeach;
                                    ?>
                                    <tr>
                                        <td><?=dataHora_BR($ln->data_cadastro); ?></td>
                                        <td>#<?=$ln->id_chamados; ?></td>                                        
                                        <td><?=$ln->assunto; ?></td>                            
                                        <td><?=$urgencia; ?></td>
                                        <td><?=$n_usuario; ?></td>
                                        <td><?=$n_funcionario; ?></td>
                                        <td><?=$status_chamado; ?></td>
                                        <td>
                                            <a href="<?=base_url('/admin/chamados/ver/'.$ln->id_chamados); ?>" title="Visualizar chamado" class="btn btn-info" target="_blank"><i class="glyphicon glyphicon-eye-open"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                                <?php } ?>
                            
                        </div><!--#chamados-->
                        <!-- USUÁRIOS -->
                        <div role="tabpanel" class="tab-pane" id="usuarios" style="padding: 10px;">
                            <?php
                                if(count($usuarios) == 0){
                                    echo '<pre>Não há usuários cadastrados neste cliente.</pre>';
                                }else{
                            ?>
                             <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Usuário</th>
                                        <th>E-mail</th>                            
                                        <th>Perfil</th>                                                                                               
                                        <th>Permissão de acesso</th>                                                                                               
                                        <th>Status</th>                                                                                               
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
                                        <td><?=($us->status == 1) ? '<span class="label label-success">Ativo</span>' : '<span class="label label-warning">Inativo</span>'; ?></td>
                                        <td>
                                            <a href="javascript:void()" title="Editar dados" class="btn btn-info" onclick="editar_usuario(<?=$us->idclientes_usuarios; ?>)"><i class="fa fa-edit"></i></a>
                                            <a href="javascript:void()" title="Excluir usuário" class="btn btn-danger delUsuario" data-toggle="modal" data-nome="<?=$us->nome; ?>" data-id="<?=$us->idclientes_usuarios; ?>" data-target="#delUsuario"><i class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>  
                                <?php } ?>
                        </div><!--#usuarios-->
                        <!-- LOGS-->
                        <div role="tabpanel" class="tab-pane" id="logs" style="padding: 10px;">
                            <a href="<?=base_url('admin/protocolos'); ?>" title="Ver relatórios completos" class="label label-info" style="margin: 5px"><i class="glyphicon glyphicon-list-alt"></i> Relatórios completos</a>
                            <br /><br />
                            <table class="table table-striped table-bordered table-hover" id="dataTables-protocolos" style="width: 100% !important">
                                <thead>
                                    <tr>
                                        <th>Data Registro</th>                                        
                                        <th>Usuário</th>                                                                                        
                                        <th>Sessão</th>                                                                                        
                                        <th>Ação</th>                                                                                        
                                        <th>IP</th>                                                                                                                                
                                    </tr>
                                </thead>
                                    
                            </table>
                            
                        </div><!--#logs-->
                                                  
                    </div>

                </div>
                
                <?php } ?>
            </div><!--.col-lg-12-->
            
    </section><!--.wrapper-->
</section><!--#main-content-->

<!-- Modal ADD ARQUIVO-->
<div class="modal fade" id="addArquivo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <form action="<?=base_url('/admin/arquivos/add'); ?>" method="post" id="frmAddArquivo"  enctype="multipart/form-data">
        <div class="modal-content">  
        
            <div class="modal-header"> 
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Cadastrar Documento</h4>
            </div>
            <div class="modal-body">
                <div class="msg_return"></div>

                    <div class="msg_return2"></div>
                    <div class="form-group">
                        <label for="responsavel">Responsável pelo envio</label>
                        <input type="text" id="responsavel" class="form-control" value="<?=$this->session->userdata('nome'); ?>" disabled />
                        <input type="hidden" name="usuario" value="<?=$this->session->userdata('id'); ?>" />
                    </div>
                    <div class="form-group">
                        <label for="cliente">Cliente</label>
                        <input type="text" value="<?=$cliente->razao_social?>" class="form-control" disabled />
                        <input type="hidden" value="<?=$cliente->id_clientes; ?>" name="cliente" />                        
                    </div>

                    <div class="form-group">
                        <label for="tipo">Tipo de documento</label>
                        <select name="tipo" class="form-control" required>
                            <option value="">Selecione um tipo de documento</option>
                            <?php
                              foreach($tipos as $tp):
                                  echo '<option value="'.$tp->id_tipo.'">'.$tp->nome_tipo.'</option>';
                              endforeach;
                            ?>
                        </select>
                    </div>

                     <div class="form-group">
                          <label for="arquivo">Documento</label>
                          <input type="file" name="userfile" class="form-control" id="arquivo" value="<?=set_value('userfile'); ?>" required />                          
                          <input type="hidden" name="titulo" id="nome_arquivo" />
                      </div>

                      <div class="form-group">
                          <label for="vcto">Data Vencimento</label>
                          <input type="text" name="vcto" class="form-control datepicker" id="vcto" value="<?=set_value('vcto'); ?>" required />
                      </div>               

            </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>        
        <input type="submit" class="btn btn-primary" id="btnAddArquivo" value="Salvar" />
        <input type="hidden" name="referencia" value="1" />
        <input type="hidden" name="rota" value="1" />
      </div>    
    </div>
    </form>
  </div>
</div>


<!-- Modal Excluir Arquivos-->
<div class="modal fade" id="delArquivo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="<?=base_url('admin/arquivos/excluir'); ?>" method="post" enctype="multipart/form-data">
      <div class="modal-header" style="background: #d9534f;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Exclusão de Documentos</h4>
      </div>
      <div class="modal-body">
          <p>Deseja realmente EXCLUIR PERMANENTEMENTE o documento <b class="nome_arquivo"></b> ou manda-lo para lixeira?</p>
          <p>Ao excluir este registro, automaticamente serão deletados todas as informações vinculadas a ele no sistema.</p>
          <p>Após a confirmação de exclusão permanente, esta ação não poderá ser desfeita.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <input type="hidden" name="id" id="id_arquivo" />
        <input type="hidden" name="arquivo" class="n_arquivo" />        
        <a id="arquivo_lixeira" class="btn btn-warning"><i class="glyphicon glyphicon-trash"></i> Lixeira</a>
        <input type="hidden" name="rota" value="1" />
      </div>
    </form>
    </div>
  </div>
</div>

<!-- Modal Excluir Usuario-->
<div class="modal fade" id="delUsuario" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="<?=base_url('/admin/clientes/excluir_usuario'); ?>" method="post" enctype="multipart/form-data">
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
        <input type="hidden" name="rota" value="1" />
        <input type="hidden" name="cliente" value="<?=$cliente->id_clientes; ?>" />
        <button type="submit" class="btn btn-danger">Excluir permanentemente</button>
      </div>
    </form>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addUsuario" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="<?=base_url('/admin/clientes/add_usuario'); ?>" method="post" enctype="multipart/form-data">
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
              <select name="tipo" class="form-control">
                  <option value="2">Funcionário</option>
                  <option value="1">Gestor</option>
              </select>
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
        <input type="hidden" name="cliente" value="<?=$cliente->id_clientes; ?>" />
        <input type="hidden" name="rota" value="1" />
      </div>
    </form>
    </div>
  </div>
</div>

<script src="<?=base_url('layout/admin/js/jquery.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">

$(document).ready(function(){
    
    // nomeação do arquivo]
    $('#arquivo').on('change', function(){
        
        var n_arquivo = $(this).val().split("\\").pop();
        // remove a extensão do arquivo
        var nome_arquivo = n_arquivo.split(".");
        
        // escreve o nome do arquivo
        $('#nome_arquivo').val(n_arquivo);
    });
    
    // modal de exclusão de clientes
    $('.delArquivo').on('click', function(){
        var nome = $(this).attr('data-nome');
        var id = $(this).attr('data-id');
        
        $('.nome_arquivo').html(nome);
        $('#id_arquivo').val(id);
        $('#arquivo_lixeira').attr('href', '<?=base_url('/admin/arquivos/lixeira/'); ?>/'+id+'/1');
    });
    // modal de exclusão de usuários
    $('.delUsuario').on('click', function(){
        var nome = $(this).attr('data-nome');
        var id = $(this).attr('data-id');

        $('.nome_usuario').html(nome);
        $('#id_user').val(id);
    });
    
    // tabela de protocolos    
    $('#dataTables-protocolos').DataTable({
                
        'ajax':'<?=base_url('/admin/protocolos/listalogs_cliente/'.$cliente->id_clientes); ?>',                   
        'order': [[ 0, "desc" ]],                                
        'columns': [
            { 'data':'datareg'},            
            { 'data':'usuario'},
            { 'data':'sessao'},
            { 'data':'acao'},
            { 'data':'ip'}                  
        ]        
    });  
    
    

});

    
    
// envia mensagem de notificação para o cliente
function envia_msg(cliente, arquivo){
    $.ajax({
        type: 'POST',
        data: {'cliente': cliente, 'arquivo': arquivo},
        url: '<?=base_url('admin/arquivos/modal_email'); ?>',
        beforeSend: function(){
            $('.retorno_msg').html('<div class="alert alert-warning">Aguarde, carregando mensagem...</div>');
        },
        success: function(e){
            $('.retorno_msg').html('');
            $('#retorno_ajax').html(e);
        },
        error: function(){
            $('.retorno_msg').html('<div class="alert alert-danger">Erro ao carregar mensagem, tente novamente.</div>');
        }
    });
}

function protocolo_download(cliente, arquivo){
    $.ajax({
        type: 'POST',
        data: {'cliente':cliente, 'arquivo':arquivo},
        url: '<?=base_url('/admin/arquivos/protocolo_download'); ?>',
        success: function(){
            location.reload();
        },
        error: function(){
            alert('Erro ao registrar protocolo');
        }
    });
}    
    
// editar usuário de cliente
function editar_usuario(usuario){
    $.ajax({
        type: 'POST',
        data: {'usuario': usuario},
        url: '<?=base_url('admin/clientes/modal_usuario'); ?>',
        success: function(e){
            $('#retorno_ajax').html(e);
        },
        error: function(){
            $('.retorno_msg').html('<div class="alert alert-danger">Erro ao buscar usuário, tente novamente.</div>');
        }
    });
}
</script>