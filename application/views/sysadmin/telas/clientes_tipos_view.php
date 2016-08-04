<?php
if(count($tipos) == 0){
    echo '<pre>Não há tipos de clientes cadastrados no momento.</pre>';
}else{
?>
<table class="table table-condensed table-hover table-striped">
    <thead>
        <tr>
            <th>Ramo</th>
            <th>Opções</th>
        </tr>
    </thead>
    <tbody>        
        <?php foreach($tipos as $ln): ?>
        <tr>
            <td><?=$ln->nome_tipo; ?></td>
            <td>
                <a href="javascript:void()" title="Editar ramo" onclick="editar_tipo(<?=$ln->id_tipo; ?>, '<?=$ln->nome_tipo; ?>')" class="btn btn-info"><i class="fa fa-edit"></i></a>
                <a href="javascript:void()" title="Excluir ramo" onclick="excluir_tipo(<?=$ln->id_tipo; ?>, '<?=$ln->nome_tipo; ?>')" class="btn btn-danger"><i class="fa fa-trash-o"></i></a>
            </td>
        </tr>        
        <?php endforeach;?>
    </tbody>    
</table>
<?php } ?>