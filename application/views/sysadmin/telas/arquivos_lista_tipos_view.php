
<div class="margin10">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist"> 
        <li role="presentation" class="active"><a href="#novos_<?=$tipo; ?>" class="controle_tab" aria-controls="novos_<?=$tipo; ?>" role="tab" data-toggle="tab">Não abertos <span class="badge"><?=count($arquivos_novos); ?></span></a></li>
        <li role="presentation"><a href="#abertos_<?=$tipo; ?>" class="controle_tab" aria-controls="abertos_<?=$tipo; ?>" role="tab" data-toggle="tab">Abertos <span class="badge"><?=count($arquivos_abertos); ?></span></a></li>
        <li role="presentation"><a href="#vencidos_<?=$tipo; ?>" class="controle_tab" aria-controls="vencidos_<?=$tipo; ?>" role="tab" data-toggle="tab">Vencidos <span class="badge"><?=count($arquivos_vencidos); ?></span></a></li>
        <li role="presentation"><a href="#excluidos_<?=$tipo; ?>" class="controle_tab" aria-controls="excluidos_<?=$tipo; ?>" role="tab" data-toggle="tab">Excluídos <span class="badge"><?=count($arquivos_excluidos); ?></span></a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="novos_<?=$tipo; ?>">
            <?php
                if(count($arquivos_novos) == 0){
                    echo '<pre>Não há arquivos novos no momento.</pre>';
                }else{
            ?>
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>Arquivo</th>
                        <th>Cliente</th>                        
                        <th>Enviado por</th>
                        <th>Cadastro Documento</th>
                        <th>Vencimento Documento</th>
                        <th>Status</th>
                        <th>Opções</th>
                    </tr>
                </thead>                    
                <tbody>
                    <?php 
                        foreach($arquivos_novos as $ln): 
                            /********************************************** 
                             * pega dados cliente
                             *********************************************/ 
                            $nome_cliente = '';                                
                            foreach($clientes as $cl):
                                if($cl->id_clientes == $ln->id_clientes):
                                    $nome_cliente = $cl->razao_social;                                        
                                endif;                                
                            endforeach;
                            /********************************************** 
                             * pega dados funcionário responsável
                             *********************************************/ 
                            $resp_arquivo = '';
                            foreach($usuarios as $us):
                                if($us->id == $ln->id_usuario):
                                    $resp_arquivo = $us->nome;
                                endif;
                            endforeach;
                    ?>
                    <tr>
                        <td><?=anchor('uploads/arquivos/'.$ln->arquivo, $ln->titulo, 'target="_blank"'); ?></td>
                        <td><?=$nome_cliente; ?></td>                        
                        <td><?=$resp_arquivo; ?></td>
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
                            <a href="javascript:void()" title="Excluir arquivo" class="btn btn-danger btn-sm delArquivo" data-toggle="modal" data-nome="<?=$ln->titulo; ?>" data-id="<?=$ln->idarquivos; ?>" data-target="#delArquivo" data-sts="<?=$ln->status; ?>"><i class="fa fa-trash-o"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
                <?php } ?>
        </div>
        <div role="tabpanel" class="tab-pane" id="abertos_<?=$tipo; ?>">
            <?php
                if(count($arquivos_abertos) == 0){
                    echo '<pre>Não há arquivos abertos no momento.</pre>';
                }else{
            ?>
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>Arquivo</th>
                        <th>Cliente</th>                        
                        <th>Enviado por</th>
                        <th>Cadastro Documento</th>
                        <th>Vencimento Documento</th>
                        <th>Status</th>
                        <th>Opções</th>
                    </tr>
                </thead>                    
                <tbody>
                    <?php 
                        foreach($arquivos_abertos as $lna): 
                            /********************************************** 
                             * pega dados cliente
                             *********************************************/ 
                            $nome_cliente = '';                                
                            foreach($clientes as $cl):
                                if($cl->id_clientes == $lna->id_clientes):
                                    $nome_cliente = $cl->razao_social;                                        
                                endif;                                
                            endforeach;
                            /********************************************** 
                             * pega dados funcionário responsável
                             *********************************************/ 
                            $resp_arquivo = '';
                            foreach($usuarios as $us):
                                if($us->id == $lna->id_usuario):
                                    $resp_arquivo = $us->nome;
                                endif;
                            endforeach;
                    ?>
                    <tr>
                        <td><?=anchor('uploads/arquivos/'.$lna->arquivo, $lna->titulo, 'target="_blank"'); ?></td>
                        <td><?=$nome_cliente; ?></td>                        
                        <td><?=$resp_arquivo; ?></td>
                        <td><?=dataHora_BR($lna->data_cadastro); ?></td>                            
                        <td><?=dataBR($lna->data_vencimento); ?></td>                            
                        <td>
                            <?php                                    
                                switch($lna->status){
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
                            <a href="javascript:void()" title="Excluir arquivo" class="btn btn-danger btn-sm delArquivo" data-toggle="modal" data-nome="<?=$lna->titulo; ?>" data-id="<?=$lna->idarquivos; ?>" data-target="#delArquivo"  data-sts="<?=$lna->status; ?>"><i class="fa fa-trash-o"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
                <?php } ?>

        </div>
        <div role="tabpanel" class="tab-pane" id="vencidos_<?=$tipo; ?>">
            <?php
                if(count($arquivos_vencidos) == 0){
                    echo '<pre>Não há arquivos vencidos no momento.</pre>';
                }else{
            ?>
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>Arquivo</th>
                        <th>Cliente</th>                        
                        <th>Enviado por</th>
                        <th>Cadastro Documento</th>
                        <th>Vencimento Documento</th>
                        <th>Status</th>
                        <th>Opções</th>
                    </tr>
                </thead>                    
                <tbody>
                    <?php 
                        foreach($arquivos_vencidos as $lnv): 
                            /********************************************** 
                             * pega dados cliente
                             *********************************************/ 
                            $nome_cliente = '';                                
                            foreach($clientes as $cl):
                                if($cl->id_clientes == $lnv->id_clientes):
                                    $nome_cliente = $cl->razao_social;                                        
                                endif;                                
                            endforeach;
                            /********************************************** 
                             * pega dados funcionário responsável
                             *********************************************/ 
                            $resp_arquivo = '';
                            foreach($usuarios as $us):
                                if($us->id == $lnv->id_usuario):
                                    $resp_arquivo = $us->nome;
                                endif;
                            endforeach;
                    ?>
                    <tr>
                        <td><?=anchor('uploads/arquivos/'.$lnv->arquivo, $lnv->titulo, 'target="_blank"'); ?></td>
                        <td><?=$nome_cliente; ?></td>                        
                        <td><?=$resp_arquivo; ?></td>
                        <td><?=dataHora_BR($lnv->data_cadastro); ?></td>                            
                        <td><?=dataBR($lnv->data_vencimento); ?></td>                            
                        <td>
                            <?php                                    
                                switch($lnv->status){
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
                            <a href="javascript:void()" title="Excluir arquivo" class="btn btn-danger btn-sm delArquivo" data-toggle="modal" data-nome="<?=$lnv->titulo; ?>" data-id="<?=$lnv->idarquivos; ?>" data-target="#delArquivo"  data-sts="<?=$lnv->status; ?>"><i class="fa fa-trash-o"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
                <?php } ?>

        </div>
        <div role="tabpanel" class="tab-pane" id="excluidos_<?=$tipo; ?>">
            <?php
                if(count($arquivos_excluidos) == 0){
                    echo '<pre>Não há arquivos excluidos no momento.</pre>';
                }else{
            ?>
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>Arquivo</th>
                        <th>Cliente</th>                        
                        <th>Enviado por</th>
                        <th>Cadastro Documento</th>
                        <th>Vencimento Documento</th>
                        <th>Status</th>
                        <th>Opções</th>
                    </tr>
                </thead>                    
                <tbody>
                    <?php 
                        foreach($arquivos_excluidos as $lnv): 
                            /********************************************** 
                             * pega dados cliente
                             *********************************************/ 
                            $nome_cliente = '';                                
                            foreach($clientes as $cl):
                                if($cl->id_clientes == $lnv->id_clientes):
                                    $nome_cliente = $cl->razao_social;                                        
                                endif;                                
                            endforeach;
                            /********************************************** 
                             * pega dados funcionário responsável
                             *********************************************/ 
                            $resp_arquivo = '';
                            foreach($usuarios as $us):
                                if($us->id == $lnv->id_usuario):
                                    $resp_arquivo = $us->nome;
                                endif;
                            endforeach;
                    ?>
                    <tr>
                        <td><?=anchor('uploads/arquivos/'.$lnv->arquivo, $lnv->titulo, 'target="_blank"'); ?></td>
                        <td><?=$nome_cliente; ?></td>                        
                        <td><?=$resp_arquivo; ?></td>
                        <td><?=dataHora_BR($lnv->data_cadastro); ?></td>                            
                        <td><?=dataBR($lnv->data_vencimento); ?></td>                            
                        <td>
                            <?php                                    
                                switch($lnv->status){
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
                            <a href="javascript:void()" title="Excluir arquivo" class="btn btn-danger btn-sm delArquivo" data-toggle="modal" data-nome="<?=$lnv->titulo; ?>" data-id="<?=$lnv->idarquivos; ?>" data-target="#delArquivo" data-sts="<?=$lnv->status; ?>"><i class="fa fa-trash-o"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
                <?php } ?>

        </div>
    </div>

</div>
