<section id="main-content">
    <section class="wrapper">

        <div class="row">
            <div class="col-lg-12 main-chart">
                <!-- ********************************icone home******************************** -->
                <div class="row">
                    <!-- icone -->
                    <div class="col-lg-3 icons box-background">
                        <a href="<?=base_url('/admin/arquivos'); ?>" title="Ir para arquivos">
                            <div class="col-md-6 chamada-icone">
                                <img src="<?=base_url('/layout/admin/img/icon_arquivos.png'); ?>" />
                            </div>
                            <div class="col-md-6 chamada-desc">
                                <span class="chamada-num"><?=count($arquivos); ?></span><br/>
                                Documentos ativos no servidor
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 icons box-background">
                        <a href="<?=base_url('/admin/clientes'); ?>" title="Ir para clientes">
                            <div class="col-md-6 chamada-icone">
                                <img src="<?=base_url('/layout/admin/img/icon_clientes.png'); ?>" />
                            </div>
                            <div class="col-md-6 chamada-desc">
                                <span class="chamada-num"><?=count($clientes); ?></span><br/>
                                Clientes cadastrados
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 icons box-background">
                        <a href="<?=base_url('/admin/usuarios'); ?>" title="Ir para usuarios">
                            <div class="col-md-6 chamada-icone">
                                <img src="<?=base_url('/layout/admin/img/icon_usuarios.png'); ?>" />
                            </div>
                            <div class="col-md-6 chamada-desc">
                                <span class="chamada-num"><?=count($usuarios); ?></span><br/>
                                Usuários cadastrados
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 icons box-background">
                        <a href="<?=base_url('/admin/tarefas'); ?>" title="Ir para tarefas">
                            <div class="col-md-6 chamada-icone">
                                <img src="<?=base_url('/layout/admin/img/icon_tarefas.png'); ?>" />
                            </div>
                            <div class="col-md-6 chamada-desc">
                                <span class="chamada-num"><?=count($tarefas); ?></span><br/>
                                Tarefas em aberto
                            </div>
                        </a>
                    </div>
                    <!-- formulario -->
                </div>                                
                
                <div class="row">
                <!--Proximas tarefas -->
                <div class="col-md-8">

                    <div class="row">
                    <div class="col-md-12">
                       
                        <h3 style="padding-left: 0.8em; color:#6178a1;">ENVIO EXPRESS</h3>

                        <!-- formulario de envio -->
                        <div class="col-md-12 background-tarefa">
                            <div class="row">
                                <form method="post"  action="<?=base_url('/admin/arquivos/upload_arquivos')?>" id="form_up_imagem_post" enctype="multipart/form-data">
                                    <div class="uploadFile">
                                        <input type="file" name="docs[]" id="file" class="inputfile" multiple />
                                        <label for="file"><strong><i class="fa fa-upload"></i> Selecione os documentos</strong></label>
                                    </div>
                                    <div class="progresso" style="display: none; width: 95%; margin: 10px auto">
                                        <progress value="0" max="100" class="form-control" style="width: 100%"></progress><span id="porcentagem">0%</span>
                                    </div>                                   
                                                                       
                                </form>
                                
                                <form class="frmDados margin10" id="formDocExpress">
                                    <div class="formDados">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="cliente">Cliente</label>
                                                <select name="cliente" id="slcCliente" class="form-control">
                                                    <option value="">Selecione um cliente</option>
                                                    <?php
                                                        foreach($clientes as $cli):
                                                            echo '<option value="'.$cli->id_clientes.'">'.$cli->razao_social.'</option>';
                                                        endforeach;
                                                    ?>
                                                </select>
                                            </div>
                                            
                                            <div class="form-group servicos">
                                                <label for="servicos">Tipo de Documento</label>
                                                <div class="listaServico">
                                                    <select name="servicos" class="form-control servicos_cliente">
                                                        <option value="">Selecione um cliente primeiro</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="vcto">Validade</label>
                                                <input type="text" name="validade" class="form-control validadeDoc datepicker" />
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-6">
                                            <div id="aviso_upload"></div> 
                                            
                                            <div class="form-group">
                                                <label for="documentos">Documentos Selecionados</label>
                                                <div class="retorno_docs">
                                                    <div class="alert alert-warning">Clique no campo acima para selecionar os documentos.</div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-12 btnAcoes text-right" style="display: none">
                                            <div class="msg_envio"></div>
                                            <a href="javascript:void()" title="Enviar Documentos" class="btn btn-primary btnEnvia" onclick="enviaDocumentos()">Enviar</a>
                                        </div>
                                    </div><!--.formDados-->
                                </form>                                
                                
                            </div> <!-- .row formulario de envio -->                            
                        
                        </div> <!-- .formulario de envio -->                    
                        
                    </div>
                </div><!--.row-->
                       
                
                <div class="row mt">
                        <div class="col-md-12 col-sm-12">
                            
                              <div class="white-panel pn">
                                <div class="white-header">
                                    <h5 class="tituloHome"><a href="javascript:void()" name="tarefaCliente">Solicitações de serviços em Aberto</a></h5>
                                </div>
                                <div class="row form-arquivo"> 
                                    
                                    <table class="table table-hover tarefas">
                                        <tbody>
                                            <?php
                                            
                                                if(count($tarefas_clientes) == 0){
                                                    echo '<pre style="margin: 10px;">Não há tarefas solicitadas no momento.</pre>';
                                                }else{
                                                    foreach($tarefas_clientes as $trfc):
                                                        
                                                        $cliente = $this->clientes->lista_cliente_id($trfc->id_cliente);                                                                                                                
                                                        $categoria = $this->tarefas->lista_tipo_id($trfc->tarefas_categorias_id_categoria);                                                        

                                                        // formata status
                                                        switch ($trfc->status){
                                                            case 0:
                                                                $status_tarefa = '<div class="label label-default">Não atendido</div>';
                                                            break;
                                                            case 1:
                                                                $status_tarefa = '<div class="label label-info">Trabalhando</div>';
                                                            break;
                                                            case 2:
                                                                $status_tarefa = '<div class="label label-primary">Avaliando</div>';
                                                            break;
                                                            case 3:
                                                                $status_tarefa = '<div class="label label-success">Entregue</div>';
                                                            break;
                                                            case 4:
                                                                $status_tarefa = '<div class="label label-warning">Pausada</div>';
                                                            break;
                                                        }     

                                                        // monta botão de status de acordo com o status da tarefa
                                                        // 0 = mostra botão de iniciar tarefa
                                                        // 1 = Mostra botão de pausar tarefa
                                                        // 2 = Mostra o botão de avaliando
                                                        // 3 = Mostra botão de entregue e desabilita demais
                                                        // 4 = Mostra botão de tarefa pausada
                                                        switch ($trfc->status){
                                                            case 0:
                                                                $btnStatus = '<a href="'.base_url('/admin/tarefas/status_tarefa/'.$trfc->id_tarefa.'/1').'" title="Trabalhar Tarefa" id="btnAcao" class="btn btn-info btn-sm"><i class="glyphicon glyphicon-play"></i> Íniciar</a>';
                                                            break;
                                                            case 1:
                                                                $btnStatus = '<a href="'.base_url('/admin/tarefas/status_tarefa/'.$trfc->id_tarefa).'/4" title="Pausar Tarefa" id="btnAcao" class="btn btn-warning btn-sm"><i class="glyphicon glyphicon-pause"></i> Pausar</a>';
                                                            break;
                                                            case 2:
                                                                $btnStatus = '<a href="'.base_url('/admin/tarefas/status_tarefa/'.$trfc->id_tarefa.'/1').'" title="Avaliando Tarefa" id="btnAcao" class="btn btn-info btn-sm"><i class="glyphicon glyphicon-unchecked"></i> Avaliando</a>';
                                                            break;
                                                            case 3:
                                                                $btnStatus = '<a href="javascript:void()" title="Tarefa entregue" id="btnAcao" class="btn btn-success" disabled><i class="glyphicon glyphicon-check btn-sm"></i> Entregue</a>';
                                                            break;
                                                            case 4:
                                                                $btnStatus = '<a href="'.base_url('/admin/tarefas/status_tarefa/'.$trfc->id_tarefa.'/1').'" title="Re-iniciar tarefa" id="btnAcao" class="btn btn-info btn-sm"><i class="glyphicon glyphicon-play"></i> Trabalhar</a>';
                                                            break;
                                                        }
                                                        
                                                        // verifica se a tarefa esta atrasada ou não
                                                        if($trfc->data_validade < date('Y-m-d') && $trfc->data_validade != '0000-00-00'){
                                                            $valTarefa = '<span class="label label-danger">Tarefa atrasada</span>';
                                                            $classTarefa = 'class="text-danger"';
                                                        }else{
                                                            $valTarefa = '';
                                                            $classTarefa = '';
                                                        }
                                            ?>
                                                        <tr <?=$classTarefa; ?>>                        
                                                            <td>
                                                                <h4><a href="javascript:void()" title="Abrir tarefa" onclick="abre_tarefa(<?=$trfc->id_tarefa; ?>)"><?=$trfc->id_tarefa; ?> - <?=$trfc->titulo; ?></a></h4>
                                                                <?=$cliente->razao_social.' > '.$categoria->nome; ?>
                                                            </td>
                                                            <td>
                                                                Começa em: <?=dataHora_BR($trfc->data_inicio); ?><br />
                                                                Finaliza em: <?=($trfc->data_validade == '0000-00-00') ? 'N/d' : dataHora_BR($trfc->data_validade); ?><br />
                                                                <?=$valTarefa; ?>
                                                            </td>
                                                            <td><?=$status_tarefa; ?></td>                                                    
                                                                <?php
                                                                    if($trfc->usuarios_responsaveis == $this->session->userdata('id')){
                                                                       echo "<td>".$btnStatus."</td>"; 
                                                                    }                                
                                                                ?>                        
                                                        </tr>
                                            <?php 
                                                    endforeach;
                                                } ?>
                                        </tbody>                                
                                    </table>
                                    
                                </div>
                              </div>
                        </div>
                    </div>
                
                
                            <h3 style="padding-left: 0.8em; color:#6178a1;">PRÓXIMAS TAREFAS</h3>

                            <div class="row">                                
                                <div class="background-tarefa">
                                    <div class="btn-tarefa col-md-12">                                            
                                    <div class="col-md-2">
                                        <a href="#addTarefa" data-toggle="modal" data-target="#addTarefa" class="btn btn-primary">Adicionar nova</a>
                                    </div>
                                    <div class="col-md-2">
                                        <a href="<?=base_url('/admin/tarefas'); ?>" title="Ver todas" class="btn btn-default" style="margin: 0 5px">Ver todas</a>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="btn-group" data-toggle="buttons">
                                            <label class="btn btn-primary active" onclick="lista_todasTarefas()">
                                                <input type="radio" name="status" id="option2" class="muda_lista" value="1" autocomplete="off"> Todas
                                            </label>                                        
                                            <label class="btn btn-primary" onclick="lista_tarefas(0)">
                                                <input type="radio" name="status" id="option1" class="muda_lista" value="0" autocomplete="off" checked> Novas
                                            </label>                                                                                                                                                                                                
                                            <label class="btn btn-primary" onclick="lista_tarefas(1)">
                                                <input type="radio" name="status" id="option2" class="muda_lista" value="1" autocomplete="off"> Trabalhando
                                            </label>                                        
                                            <label class="btn btn-primary" onclick="lista_tarefas(4)">
                                                <input type="radio" name="status" id="option3" class="muda_lista" value="1" autocomplete="off"> Pausadas
                                            </label>                                                                                    
                                        </div>                                                                            
                                    </div>
                                    </div>
                                    <div class="background-tb-tarefa">
                                        <div class="listaTarefas"></div>
                                    </div>                                    
                                </div>
                                
                            </div>

                            <div class="row">
                                <!-- Documentos expirados-->
                                <div class="col-md-12"> <!-- Documentos expirados-->
                                    <h3 style="padding-left: 0.8em; color:#6178a1;">Documentos que expiram nos próximos 5 dias (<?=dataBR(SomarData(date('Y-m-d'), 5, 0, 0)); ?>)</h3> 
                                    
                                    <?php
                                        if(count($arquivos_vencer) == 0){
                                            echo '<div class="alert alert-warning">Não há documentos a vencer nos próximos 5 dias.</div>';
                                        }else{
                                    ?>
                                    
                                    <table  class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th> Documento </th>
                                                <th> Clente </th>                                        
                                                <th> Enviado por </th>                                        
                                                <th> Cadastro Documento </th>
                                                <th> Vencimento Documento </th>
                                                <th> Status </th>
                                            </tr>                                        
                                        </thead>
                                        <tbody>
                                            <?php 
                                                foreach($arquivos_vencer as $vcto): 
                                                    // resgata nome do cliente
                                                    $cliente = $this->clientes->lista_cliente_id($vcto->id_clientes);                                                
                                                    // resgata nome do usuário que enviou o arquivo
                                                    $user = $this->usuarios->lista_usuario_id($vcto->id_usuario);
                                                    echo '<tr>';
                                                        echo '<td>'.$vcto->titulo.'</td>';
                                                        echo '<td>'.$cliente->razao_social.'</td>';
                                                        echo '<td>'.$user->nome.'</td>';
                                                        echo '<td>'; 
                                                        dataHora_BR($vcto->data_cadastro);
                                                        echo '</td>';
                                                        echo '<td>';
                                                        dataBR($vcto->data_vencimento);
                                                        echo '</td>';
                                                        echo '<td>';
                                                        getStatusDoc($vcto->status);
                                                        echo '</td>';
                                                    echo '</tr>';
                                                endforeach; 
                                            ?>
                                        </tbody>                                        
                                    </table>
                                    <?php } ?>
                                </div>
                            </div> <!-- .Documentos expirados-->
                            
                             <div class="row">
                                <!-- Documentos enviados-->
                                <div class="col-md-12"> 
                                    <h3 style="padding-left: 0.8em; color:#6178a1;">Útimo documentos enviados</h3>
                                    <?php
                                        if(count($ultimos_arquivos) == 0){
                                            echo '<div class="alert alert-warning">Ainda não foi enviado nenhum tipo de documento.</div>';
                                        }else{
                                    ?>
                                    <table  class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Documento</th>
                                                <th>Cliente</th>
                                                <th>Enviado por</th>
                                                <th>Cadastro Documento</th>
                                                <th>Vencimento Documento</th>
                                                <th>Status</th>
                                            </tr>                                        
                                        </thead>
                                        <tbody>
                                            <?php
                                                foreach($ultimos_arquivos as $ua):
                                                    // resgata nome do cliente
                                                    $cliente = $this->clientes->lista_cliente_id($ua->id_clientes);                                                
                                                    // resgata nome do usuário que enviou o arquivo
                                                    $user = $this->usuarios->lista_usuario_id($ua->id_usuario);
                                                    echo '<tr>';
                                                        echo '<td>'.$ua->titulo.'</td>';
                                                        echo '<td>'.$cliente->razao_social.'</td>';
                                                        echo '<td>'.$user->nome.'</td>';
                                                        echo '<td>';
                                                        dataHora_BR($ua->data_cadastro);
                                                        echo '</td>';
                                                        echo '<td>';
                                                        dataBR($ua->data_vencimento);
                                                        echo '</td>';
                                                        echo '<td>';
                                                        getStatusDoc($ua->status);
                                                        echo '</td>';
                                                    echo '</tr>';
                                                endforeach;
                                            ?>                                            
                                        </tbody>                                                                                                                        
                                    </table>
                                    <?php } ?>
                                </div>
                            </div> <!-- .Documentos enviados-->
                        </div>
                
                        <!-- SERVER STATUS PANELS -->
                        <div class="col-md-4 col-sm-4 mb">
                            
                            <div class="ds margin10"> <!-- documentos enviaados -->
                            <div class="white-panel pn donut-chart">
                                <div class="white-header">
                                    <h5>Documentos Enviados</h5>
                                </div>
                                <div class="row">

                                    <?php
                                    // calcula porcentagem de arquivos
                                    $total_fechado = 0;
                                    $total_aberto = 0;
                                    $total_vencidos = 0;
                                    foreach ($arquivos as $ar):

                                        // totaliza arquivos fechados (não abertos)
                                        if ($ar->status == 0):
                                            $total_fechado++;
                                        endif;
                                        // totaliza arquivos abertos
                                        if ($ar->status == 1):
                                            $total_aberto++;
                                        endif;
                                        // totaliza arquivos abertos
                                        if ($ar->status == 2):
                                            $total_vencidos++;
                                        endif;
                                    endforeach;

                                    // calcula % fechado
                                    if($total_fechado > 0){
                                        $porc_fechado = ($total_fechado * 100) / count($arquivos);
                                    }else{
                                        $porc_fechado = 0;
                                    }
                                    
                                    // calcula % abertos
                                    if($total_aberto > 0){
                                        $porc_abertos = ($total_aberto * 100) / count($arquivos);
                                    }else{
                                        $porc_abertos = 0;
                                    }
                                    
                                    // calcula % fechado
                                    if($total_vencidos > 0){
                                        $porc_vencidos = ($total_vencidos * 100) / count($arquivos);
                                    }else{
                                        $porc_vencidos = 0 ;
                                    }           
                                    ?>

                                    <div class="col-sm-5 col-xs-5 goleft">
                                        <p style="color: #5bc0de"><i class="fa fa-file"></i> <?= number_format($porc_fechado, 0); ?>% não abertos</p>
                                        <p style="color: #5cb85c"><i class="fa fa-file-o"></i> <?= number_format($porc_abertos, 0); ?>% abertos</p>
                                        <p style="color: #f0ad4e"><i class="fa fa-file-archive-o"></i> <?= number_format($porc_vencidos, 0); ?>% vencidos</p>
                                    </div>
                                </div>
                                <canvas id="serverstatus01" height="150" width="150"></canvas>
                                <script>
                                    var doughnutData = [
                                        {
                                            value: <?= $porc_fechado; ?>,
                                            color: "#5bc0de"
                                        },
                                        {
                                            value: <?= $porc_abertos; ?>,
                                            color: "#5cb85c"
                                        },
                                        {
                                            value: <?= $porc_vencidos; ?>,
                                            color: "#f0ad4e"
                                        }
                                    ];
                                    var myDoughnut = new Chart(document.getElementById("serverstatus01").getContext("2d")).Pie(doughnutData);
                                </script>
                            </div><!--/grey-panel -->

                        </div> <!-- .documentos enviaados -->
                           
                            <div class="ds margin10">
                                <!--COMPLETED ACTIONS DONUTS CHART-->
                                <h3>NOVOS CHAMADOS</h3>

                                <?php
                                if (count($chamados_novos) == 0) {
                                    echo '<div class="alert alert-warning">Não há novos chamados.</div>';
                                } else {
                                    foreach ($chamados_novos as $cham):

                                        // pega nome do cliente
                                        foreach ($clientes as $cli):
                                            if ($cli->id_clientes == $cham->id_clientes):
                                                $n_cliente = $cli->razao_social;
                                            endif;
                                        endforeach;
                                        ?>
                                        <!-- First Action -->
                                        <div class="desc">
                                            <div class="thumb">
                                                <a href="<?=base_url('/admin/chamados/ver/' . $cham->id_chamados); ?>">
                                                    <span class="badge bg-theme"><i class="fa fa-clock-o"></i></span>
                                                </a>
                                            </div>
                                            <div class="details">
                                                <a href="<?=base_url('/admin/chamados/ver/' . $cham->id_chamados); ?>">
                                                    <p><muted><?= dataHora_BR($cham->data_cadastro); ?></muted><br/>
                                                    <?= $n_cliente; ?>
                                                </a><br/> 
                                                <?= anchor('/admin/chamados/ver/' . $cham->id_chamados, '<i class="fa fa-envelope"></i> ' . $cham->assunto); ?>
                                                </p>
                                            </div>
                                        </div>
                                        <?php
                                    endforeach;
                                }
                                ?>
                    </div>
                            <div class="ds margin10">
                                <!--COMPLETED ACTIONS DONUTS CHART-->
                                <h3>CHAMADOS EM ANDAMENTO</h3>

                                <?php
                                if (count($chamados_atendimento) == 0) {
                                    echo '<div class="alert alert-warning">Você ainda não realizou nenhum atendimento.</div>';
                                } else {
                                    foreach ($chamados_atendimento as $atendimento):

                                        // pega nome do cliente
                                        foreach ($clientes as $cli):
                                            if ($cli->id_clientes == $atendimento->id_clientes):
                                                $n_cliente = $cli->razao_social;
                                            endif;
                                        endforeach;
                                        ?>
                                        <!-- First Action -->
                                        <div class="desc">
                                            <div class="thumb">
                                                <a href="<?=base_url('/admin/chamados/ver/' . $atendimento->id_chamados); ?>" title="Ver chamado">
                                                    <span class="badge bg-theme"><i class="fa fa-clock-o"></i></span>
                                                </a>
                                            </div>
                                            <div class="details">
                                                <a href="<?=base_url('/admin/chamados/ver/' . $atendimento->id_chamados); ?>" title="Ver chamado">
                                                    <p><muted><?= dataHora_BR($atendimento->data_cadastro); ?></muted><br/>
                                                    <?= $n_cliente; ?>
                                                </a><br/> 
                                                <?= anchor('/admin/chamados/ver/' . $atendimento->id_chamados, '<i class="fa fa-envelope"></i> ' . $atendimento->assunto); ?>
                                                </p>
                                            </div>
                                        </div>
                                        <?php
                                    endforeach;
                                }
                                ?>
                    </div>

                </div><!-- /col-lg-9 END SECTION MIDDLE -->

            </div><!--/row -->




    </section>
</section>

<!--main content end-->

<!-- Modal -->
<div class="modal fade" id="addTarefa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="<?=base_url('admin/tarefas/add'); ?>" method="post" enctype="multipart/form-data">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Nova Solicitação</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="titulo">Título*</label>
                    <input type="text" class="form-control" id="titulo" name="titulo" required />
                </div>
                <div class="form-group">
                    <label for="responsavel">Responsável*</label>
                    <select name="responsavel" class="form-control" id="responsavel">
                        <option value="">Selecione o responsável</option>
                        <?php
                            foreach($usuarios as $us):
                                // pré seleciona o usuário logado
                                if($this->session->userdata('id') == $us->id){
                                    $selectUser = 'selected';
                                }else{
                                    $selectUser = '';
                                }
                                
                                echo '<option value="'.$us->id.'" '.$selectUser.'>'.$us->nome.'</option>';
                            endforeach;
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tipo">Tipo de solicitação*</label>
                    <div id="setor-tarefas">
                    <select name="tipo" class="form-control" id="tipo">
                        <option value="">Selecione o tipo</option>
                        <?php
                            foreach($tipos as $tp):   
                                // se for gestor mostra tudo
                                if($this->session->userdata('servico') == 0){
                                    echo '<option value="'.$tp->id_categoria.'">'.$tp->nome.'</option>';
                                }else{
                                    // vincula as tarefas somente dos serviços.
                                    if($this->session->userdata('servico') == $tp->id_servico){
                                        echo '<option value="'.$tp->id_categoria.'">'.$tp->nome.'</option>';    
                                    }
                                    
                                }                                
                            endforeach;
                        ?>
                    </select>
                    </div><!--#setor-tarefas-->
                </div>
                <div class="form-group">
                    <label for="cliente">Cliente</label>
                    <select name="cliente" class="form-control" id="cliente">
                        <option value="">Selecione o cliente</option>
                        <?php
                            foreach($clientes as $cli):
                                echo '<option value="'.$cli->id_clientes.'">'.$cli->razao_social.'</option>';
                            endforeach;
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="cliente">Descrição</label>
                    <textarea name="descricao" class="form-control"></textarea>
                </div>
                
                <div class="row mt">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="data-inicial">Data de Ínicio</label>
                            <input type="date" name="data_inicial" class="form-control" value="<?=date('d/m/Y');?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="data-inicial">Data de Finalização</label>
                            <input type="date" name="data_final" class="form-control" value="<?=date('d/m/Y'); ?>">
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
              <button type="submit" class="btn btn-primary">Cadastrar Solicitação</button>
            </div>
        </form>
    </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
    
    lista_todasTarefas();
    
    // upload de fotos de produtos
    $("#file").on("change",function(){
          $("#aviso_upload").removeClass().addClass("alert alert-info").html("Enviando documentos, aguarde...");
          $('.progresso').css('display', 'block');          
          $('.uploadFile').css('display', 'none');
          
          if(validaExtensao("file")){
            $("#form_up_imagem_post").ajaxForm({
                uploadProgress: function(event, position, total, percentComplete) {
                    $('progress').attr('value',percentComplete);
                    $('#porcentagem').html(percentComplete+'%');
                }, 
                success: function(response){
                    $("#aviso_upload").removeClass().addClass("alert alert-success").html('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> Documento enviado com sucesso!');
                    $(".retorno_docs").html(response);
                    $("#file").val("").empty();
                    $('progress').attr('value',0);
                    $('#porcentagem').html('0%');                    
                    $('.uploadFile').css('display', 'block');
                    $('.progresso').css('display', 'none');
                    $('.btnAcoes').css('display', 'block');                    
                }
              }).submit();

            }
            
            return false;        
    }); 
    
    $('#slcCliente').on('change', function(){
    
        var cliente = $(this).val();
        
        $.ajax({
            type: 'POST',
            data: {'cliente':cliente},
            url: '<?=base_url('/admin/arquivos/tipo_arquivo_cliente'); ?>',
            beforeSend: function(){
                $('.listaServico').html('<div class="alert alert-warning">Carregando tipos de arquivos, aguarde...</div>');
            },
            success: function(info){
                $('.listaServico').html(info);
            },
            error: function(){
                $('.listaServico').html('<div class="alert alert-warning">Erro ao carregar tipos de arquivos, tente novamente.</div>');
            }
        });
    
    });
        
    
});  

// envia documentos
function enviaDocumentos(){
    var dados = $('#formDocExpress').serialize();
    
    var cliente = $('#slcCliente').val();
    var tipo = $('.servicos_cliente').val();
    var validade = $('.validadeDoc').val();
    
    if(cliente == ""){
        $('.msg_envio').html('<div class="alert alert-danger">Selecione um cliente.</div>');
        $('#slcCliente').focus();
    }else if(tipo == ""){
        $('.msg_envio').html('<div class="alert alert-danger">Selecione um tipo de arquivo.</div>');
        $('.servicos_cliente').focus();
    }else if(validade == ""){    
        $('.msg_envio').html('<div class="alert alert-danger">Informe a validade do(s) documento(s).</div>');
        $('.validadeDoc').focus();
    }else{
        $.ajax({
            type: 'POST',
            data: dados,
            url: '<?=base_url('/admin/arquivos/envia_lote'); ?>',
            beforeSend: function(){
                $('.msg_envio').html('<div class="alert alert-warning">Aguarde, enviando arquivos...</div>');
                $('.btnEnvia').attr('disabled', 'disabled');
            },
            success: function(info){
                $('.msg_envio').html('<div class="alert alert-success">Arquivos enviados com sucesso.</div>');                        
                $('.btnEnvia').removeAttr('disabled', 'disabled');            
                setTimeout(location.reload(), 5000);
            },
            error: function(){
                $('.msg_envio').html('<div class="alert alert-danger">Erro ao enviar arquivos, tente novamente.</div>');            
                $('.btnEnvia').removeAttr('disabled', 'disabled');
            }
        });
    }
}

// deleta documento da lista
function deleta_documento(doc){
    $('#lista_doc_'+doc).remove();
    // verifica qtde de elementos da lista
    var total = $('.listaDocs li').length;
    if(total == 0){
        $('.retorno_docs').html('<div class="alert alert-warning">Clique no campo acima para selecionar os documentos.</div>');
        $('.btnAcoes').css('display', 'none');
    }
}

// valida extensão da imagem
// id = parametro com tipo de extensão da imagem
function validaExtensao(id){
            
    var extensoes = new Array("bmp","jpg","jpeg","png", "pdf", "doc", "xls", "docx", "txt");

    var ext = $("#"+id).val().split(".")[1].toLowerCase();

    if($.inArray(ext, extensoes) == -1){
        alert("Arquivo não permitido: "+ext);
        $("#"+id).val("").empty();
        return false;
    }else{
        return true;
    }
}
    
    
// lista todas tarefas
function lista_todasTarefas(){
    $.ajax({
        type: 'GET',
        url: '<?=base_url('/admin/tarefas/tarefasHome'); ?>/',
        beforeSend: function(){
            $('.listaTarefas').html('<div class="alert alert-warning">Aguarde, carregando tarefas...</div>');
        },
        success: function(info){
            $('.listaTarefas').html(info);
        },
        error: function(){
            $('.listaTarefas').html('<div class="alert alert-danger">Erro ao carregar tarefas. Atualize sua página e tente novamente</div>');
        }
    });
}
// lista tarefas por status
function lista_tarefas(status){
    $.ajax({
        type: 'GET',
        url: '<?=base_url('/admin/tarefas/tarefasHome'); ?>/'+status,
        beforeSend: function(){
            $('.listaTarefas').html('<div class="alert alert-warning">Aguarde, carregando tarefas...</div>');
        },
        success: function(info){
            $('.listaTarefas').html(info);
        },
        error: function(){
            $('.listaTarefas').html('<div class="alert alert-danger">Erro ao carregar tarefas. Atualize sua página e tente novamente</div>');
        }
    });
}


    function cliente_servico(cliente) {
        $.ajax({
            type: 'POST',
            data: {'cliente': cliente},
            url: '<?= base_url('/admin/arquivos/servico_cliente'); ?>',
            beforeSend: function () {
                $('.tipo_servico').html('<div class="alert alert-warning">Aguarde, carregando serviços vinculados...</div>');
            },
            success: function (info) {
                $('.tipo_servico').html(info);
            },
            error: function () {
                $('.tipo_servico').html('<div class="alert alert-danger">Erro ao buscar serviços vinculados, tente novamente...</div>');
            }
        });
    }


</script>