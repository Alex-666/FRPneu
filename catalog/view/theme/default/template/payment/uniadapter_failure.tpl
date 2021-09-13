<?php
// Autor (c) Miroslav Novak, www.platiti.cz
// Pouzivani bez souhlasu autora neni povoleno
// #Ver:PRV081-29-gb89dde18:2020-04-17#
?>
<?php echo $header; ?>

<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?><img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>
<?php } ?>

<b><a href="<?php echo $continue_url?>"><?php echo $continue_text?></a></b>
<p/>

<?php echo $column_left; ?><?php echo $column_right; ?>

<?php echo $footer; ?>
