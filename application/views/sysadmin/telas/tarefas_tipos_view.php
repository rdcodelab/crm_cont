<?php
if(count($tipos) == 0){
    echo '<pre>Não há tipos de solicitações cadastrados no momento.</pre>';
}else{
?>
<table class="table table-condensed table-hover table-striped">
    <thead>
        <tr>
            <th>Tipos</th>
            <th>Setor</th>
            <th>Opções</th>
        </tr>
    </thead>
    <tbody>        
        <?php foreach($tipos as $ln): ?>
        <tr>
            <td><?=$ln->nome; ?></td>
            <td>
                <?php
                    $serv = $this->servicos->lista_servico_id($ln->id_servico); 
                    echo $serv->nome;
                ?>
            </td>
            <td>
                <a href="javascript:void()" title="Editar tipo" onclick="editar_tipo(<?=$ln->id_categoria; ?>, '<?=$ln->nome; ?>')" class="btn btn-info"><i class="fa fa-edit"></i></a>
                <a href="javascript:void()" title="Excluir tipo" onclick="excluir_tipo(<?=$ln->id_categoria; ?>, '<?=$ln->nome; ?>')" class="btn btn-danger"><i class="fa fa-trash-o"></i></a>
            </td>
        </tr>        
        <?php endforeach;?>
    </tbody>    
</table>
<?php } ?>