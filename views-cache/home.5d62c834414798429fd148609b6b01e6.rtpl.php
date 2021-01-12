<?php if(!class_exists('Rain\Tpl')){exit;}?><?php if( $message != '' ){ ?>
<script>
    window.onload = function(){
        M.toast({html: '<?php echo htmlspecialchars( $message, ENT_COMPAT, 'UTF-8', FALSE ); ?>'});
    }
</script>
<?php } ?>

<div class="row">
    <div class="col s12 m6 push-m3">

        <table class="striped">

        <caption><h3 class="light"; align="left">Usuários</h3></caption>
            <thead>
                <th>Nome</th>
                <th>Email</th>
                <th>Criação</th>
            </thead>
            <tbody>
                <?php $counter1=-1;  if( isset($usuarios) && ( is_array($usuarios) || $usuarios instanceof Traversable ) && sizeof($usuarios) ) foreach( $usuarios as $key1 => $value1 ){ $counter1++; ?>
                <tr>
                    <td><?php echo htmlspecialchars( $value1["Nome"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                    <td><?php echo htmlspecialchars( $value1["Email"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                    <td><?php echo formatDate($value1["Data"]); ?></td>
                    <td><a href="/admin/edit/<?php echo htmlspecialchars( $value1["Id"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" class="btn-floating yellow"><i class="material-icons">edit</i></a></td>
                    <td><a href="/admin/delete/<?php echo htmlspecialchars( $value1["Id"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" class="btn-floating red"><i class="material-icons">delete</i></a></td>
                </tr>
                <?php } ?>
            </tbody>

        </table>
<hr>

        <a href="/admin/add" class="btn" style="float: right"><i class="material-icons">add_circle</i> Adicionar Usuário</a>
    </div>
</div>