<?php
// Autor (c) Miroslav Novak, www.platiti.cz
// Pouzivani bez souhlasu autora neni povoleno
// #Ver:PRV088-14-ge016fa1f:2021-08-25#
?>

<form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" id="checkout_pti">

<?php if ($forexMessage !== null) { ?>
<div class="content">
<?php echo $forexMessage ?>
</div>
<?php } ?>
<?php echo $minilogo ?>

<?php if ($selectCsPayBrand) { ?>
<b style="margin-bottom: 2px; display: block;"><?php echo $selectCsPayBrandTitle; ?></b>
<div class="content">
	<input type="radio" name="brand" value="VISA" checked> VISA</input><br/>
	<input type="radio" name="brand" value="MasterCard"> MasterCard</input><br/>
	<input type="radio" name="brand" value="VisaElectron"> VisaElectron</input><br/>
	<input type="radio" name="brand" value="Maestro"> Maestro</input><br/>
</div>
<?php } ?>

<div class="buttons">

<?php if (VERSION < '1.5') { ?>
  <table>
    <tr>
	  <td align="left"><a onclick="location = '<?php echo str_replace('&', '&amp;', $back); ?>'" class="button"><span><?php echo $button_back; ?></span></a></td>
      <td align="right"><a onclick="$('#checkout_pti').submit();" class="button"><span><?php echo $button_confirm; ?></span></a></td>
    </tr>
  </table>
<?php } else if (VERSION < '2.0') { ?>
  <div class="right"><input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="button" onclick="$('#checkout_pti').submit();"/></div>
<?php } else { ?>
  <div class="pull-right">
    <input type="submit" value="<?php echo $button_confirm; ?>" class="btn btn-primary" />
  </div>
<?php } ?>

</div>
</form>
