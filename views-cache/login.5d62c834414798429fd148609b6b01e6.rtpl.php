<?php if(!class_exists('Rain\Tpl')){exit;}?><?php if( $erro != '' ){ ?>
<script>
    window.onload = function(){
        M.toast({html: '<?php echo htmlspecialchars( $erro, ENT_COMPAT, 'UTF-8', FALSE ); ?>'});
    }
</script>
<?php } ?>

<div class="row">
    <div class="col s12 m6 push-m3">
        <form method="POST">
            <h3 class="light"; align="center">Login</h3>
            <div class="input-field col s12">
                <input type="email" name="email" id="email">
                <label for="email"><i class="material-icons">mail</i>Email</label>
            </div>
            <div class="input-field col s12">
                <input type="password" name="senha" id="senha">
                <label for="senha"><i class="material-icons">lock</i>Senha</label>
            </div>


<hr>
        <button type="submit" class="btn green" style="float: right; margin-left: 10px"><i class="material-icons">person</i> Entrar</button>
        <a href="/" class="btn" style="float: right"><i class="material-icons">keyboard_return</i> Voltar</a>
        </form>
    </div>
</div>