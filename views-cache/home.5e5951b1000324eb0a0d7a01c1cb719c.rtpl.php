<?php if(!class_exists('Rain\Tpl')){exit;}?><h1>Esta é a Home do Site</h1>
<?php if( $nome != '' ){ ?>
<p>Bem Vindo(a) <?php echo htmlspecialchars( $nome, ENT_COMPAT, 'UTF-8', FALSE ); ?></p>
<?php } ?>