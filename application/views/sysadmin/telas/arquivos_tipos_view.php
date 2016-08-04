<?php
if(count($tipos) == 0){
    echo '<pre>Não há tipos de clientes cadastrados no momento.</pre>';
}else{
?>
<table class="table table-condensed table-hover table-striped">
    <thead>
        <tr>
            <th>Tipo de Documento</th>
            <th>Serviço</th>
            <th>Opções</th>
        </tr>
    </thead>
    <tbody>        
        <?php 
            foreach($tipos as $ln): 
                $servico = "";
                foreach ($servicos as $srv):
                    if($srv->id == $ln->id_servico){
                        $servico = $srv->nome;
                    }
                endforeach;
        ?>
        <tr>
            <td><?=$ln->nome_tipo; ?></td>
            <td><?=$servico; ?></td>
            <td>
                <a href="javascript:void()" title="Editar pasta" onclick="editar_tipo(<?=$ln->id_tipo; ?>, '<?=$ln->nome_tipo; ?>', '<?=$ln->id_servico?>')" class="btn btn-info"><i class="fa fa-edit"></i></a>
                <a href="javascript:void()" title="Excluir pasta" onclick="excluir_tipo(<?=$ln->id_tipo; ?>, '<?=$ln->nome_tipo; ?>')" class="btn btn-danger"><i class="fa fa-trash-o"></i></a>
            </td>
        </tr>        
        <?php endforeach;?>
    </tbody>    
</table>
<?php } ?>