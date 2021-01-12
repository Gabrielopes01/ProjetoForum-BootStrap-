<?php if(!class_exists('Rain\Tpl')){exit;}?><div class="row">
    <div class="col s12 m6 push-m3">
        <form method="POST">
            <h3 class="light"; align="left">Editar Usuário</h3>
            <div class="input-field col s12">
                <input type="text" name="nome" id="nome", value="<?php echo htmlspecialchars( $usuario["Nome"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                <label for="nome">Nome</label>
            </div>
            <div class="input-field col s12">
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars( $usuario["Email"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                <label for="email">Email</label>
            </div>
            <input type="hidden" id="id" name="id" value=<?php echo htmlspecialchars( $usuario["Id"], ENT_COMPAT, 'UTF-8', FALSE ); ?>>


<hr>
        <button type="submit" class="btn green" style="float: right; margin-left: 10px"><i class="material-icons">check</i> Editar</button>
        <a href="/admin" class="btn" style="float: right"><i class="material-icons">keyboard_return</i> Voltar</a>
        </form>
    </div>
</div>