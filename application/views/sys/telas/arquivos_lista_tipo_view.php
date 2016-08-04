
                            <div>

                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs" role="tablist"> 
                                    <li role="presentation" class="active"><a href="#novos" aria-controls="novos" role="tab" data-toggle="tab">Não abertos <span class="badge"><?=count($arquivos_novos); ?></span></a></li>
                                    <li role="presentation"><a href="#abertos" aria-controls="abertos" role="tab" data-toggle="tab">Abertos <span class="badge"><?=count($arquivos_abertos); ?></span></a></li>
                                    <li role="presentation"><a href="#vencidos" aria-controls="vencidos" role="tab" data-toggle="tab">Vencidos <span class="badge"><?=count($arquivos_vencidos); ?></span></a></li>                                    
                                </ul>

                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active" id="novos">

                                        <?php
                                            if(count($arquivos_novos) == 0){
                                                echo '<pre>Não há arquivos não abertos no momento.</pre>';
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
                                                    foreach($arquivos_novos as $ln): 
                                                        
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
                                                    <td><?=$ln->titulo; ?></td>                                                    
                                                    <td>
                                                        <?php
                                                            foreach($tipos as $tp):
                                                                if($tp->id_tipo === $ln->id_tipo):
                                                                    echo $tp->nome_tipo;
                                                                endif;
                                                            endforeach;
                                                        ?>
                                                    </td>
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
                                                        <a href="<?=base_url('uploads/arquivos/'.$ln->arquivo); ?>" title="Baixar arquivo" class="btn btn-default btn-sm" onclick="protocolo_download(<?=$ln->id_clientes; ?>, <?=$ln->idarquivos; ?>)" download><i class="fa fa-download"></i></a>                                                        
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                            <?php } ?>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="abertos">

                                        <?php
                                            if(count($arquivos_abertos) == 0){
                                                echo '<pre>Não há arquivos abertos no momento.</pre>';
                                            }else{
                                        ?>
                                        <table class="table table-hover table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Arquivo</th>                                                    
                                                    <th>Tipo de Arquivo</th>
                                                    <th>Enviado por</th>
                                                    <th>Cadastro Documento</th>
                                                    <th>Download Documento</th>
                                                    <th>Vencimento Documento</th>
                                                    <th>Status</th>
                                                    <th>Opções</th>
                                                </tr>
                                            </thead>                    
                                            <tbody>
                                                <?php 
                                                    foreach($arquivos_abertos as $lna):                                                         
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
                                                    <td>
                                                        <?php
                                                            foreach($tipos as $tp):
                                                                if($tp->id_tipo === $lna->id_tipo):
                                                                    echo $tp->nome_tipo;
                                                                endif;
                                                            endforeach;
                                                        ?>
                                                    </td>
                                                    <td><?=$resp_arquivo; ?></td>
                                                    <td><?=dataHora_BR($lna->data_cadastro); ?></td>                            
                                                    <td><?=dataHora_BR($lna->data_abertura); ?></td>                            
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
                                                        <a href="<?=base_url('uploads/arquivos/'.$lna->arquivo); ?>" title="Baixar arquivo" class="btn btn-default btn-sm" onclick="protocolo_download(<?=$lna->id_clientes; ?>, <?=$lna->idarquivos; ?>)" download><i class="fa fa-download"></i></a>                                                        
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                            <?php } ?>

                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="vencidos">

                                        <?php
                                            if(count($arquivos_vencidos) == 0){
                                                echo '<pre>Não há arquivos vencidos no momento.</pre>';
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
                                                </tr>
                                            </thead>                    
                                            <tbody>
                                                <?php 
                                                    foreach($arquivos_vencidos as $lnv):                                                         
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
                                                    <td><?=$lnv->titulo; ?></td>                                                    
                                                    <td>
                                                        <?php
                                                            foreach($tipos as $tp):
                                                                if($tp->id_tipo === $lnv->id_tipo):
                                                                    echo $tp->nome_tipo;
                                                                endif;
                                                            endforeach;
                                                        ?>
                                                    </td>
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
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                            <?php } ?>

                                    </div>
                                 
                                </div>

                            </div>

