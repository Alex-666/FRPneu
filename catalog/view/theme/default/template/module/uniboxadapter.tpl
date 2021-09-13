<?php
// Autor (c) Miroslav Novak, www.platiti.cz
// Pouzivani bez souhlasu autora neni povoleno
// #Ver:PRV081-29-gb89dde18:2020-04-17#
?>

<?php
$ver14 = strpos('v'.VERSION, 'v1.4')!==false;
?>

<?php if ($ver14) { ?>

<div class="box">
	<div class="top">
		<img src="catalog/view/theme/default/image/brands.png" alt="" />
		<?php echo $ibtitle; ?>
	</div>
	<div class="middle" style="text-align: left;">
		<?php if ($iblink!==null) { ?>
			<a href="<?php echo $iblink; ?>">
		<?php } ?>
		<img style="margin-top:10px" src="<?php echo $ibimage ?>" alt="platitipti">
		<?php if ($iblink!==null) { ?>
			</a>
		<?php } ?>
		<!--
		<br>
		<a href="<?php echo $platitiLink; ?>"><span style="font-size:5pt"><?php echo $platitiLinkText; ?></span></a>
		-->
	</div>
	<div class="bottom">&nbsp;</div>
</div>

<?php } else { ?>

<div class="box">
	<div class="box-heading">
		<?php echo $ibtitle; ?>
	</div>
	<div class="box-content">
		<?php if ($iblink!==null) { ?>
			<a href="<?php echo $iblink; ?>">
		<?php } ?>
		<img style="margin-top:10px" src="<?php echo $ibimage ?>" alt="<?php echo $platitiLinkText; ?> (platitipti)">
		<?php if ($iblink!==null) { ?>
			</a>
		<?php } ?>
		<!--
		<br>
		<a href="<?php echo $platitiLink; ?>"><span style="font-size:5pt"><?php echo $platitiLinkText; ?></span></a>
		-->
	</div>
</div>

<?php } ?>
