<?php if(!class_exists('Rain\Tpl')){exit;}?>
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
                <tr>
                    <?php $counter1=-1;  if( isset($usuarios) && ( is_array($usuarios) || $usuarios instanceof Traversable ) && sizeof($usuarios) ) foreach( $usuarios as $key1 => $value1 ){ $counter1++; ?>
                    <td><?php echo htmlspecialchars( $value1["Nome"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                    <td><?php echo htmlspecialchars( $value1["Email"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                    <td><?php echo formatDate($value1["Data"]); ?></td>
                    <td><a href="" class="btn-floating yellow"></a></td>
                    <td><a href="" class="btn-floating red"></a></td>
                    <?php } ?>
                </tr>
            </tbody>

        </table>
<hr>

        <a href="" class="btn" style="float: right">Adicionar Usuário</a>
    </div>
</div>