<?php
// Autor (c) Miroslav Novak, www.platiti.cz
// Pouzivani bez souhlasu autora neni povoleno
// #Ver:PRV081-14-g21587217:2020-02-29#

if (isset($_REQUEST['OK']) || isset($_REQUEST['KO'])) {
	$queryStr = "numwrk=".$_REQUEST['numwrk'];
	$queryStr .= "&jmeno=".$_REQUEST['jmeno'];
	$queryStr .= "&prijmeni=".$_REQUEST['prijmeni'];
	$queryStr .= "&splatka=".$_REQUEST['splatka'];
	$queryStr .= "&numklient=".$_REQUEST['numklient'];
	$queryStr .= "&obj=".$_REQUEST['obj'];
	if (isset($_REQUEST['OK'])) {
		$queryStr .= "&stav=1&numaut=".$_REQUEST['numaut'];
		$url=appendUrlParams($_POST['url_back_ok'], $queryStr);
	} else if (isset($_REQUEST['KO'])) {
		$queryStr .= "&stav=2&vdr=".$_REQUEST['vdr'];
		$url=appendUrlParams($_POST['url_back_ko'], $queryStr);
	}
	Header("Location:".$url);
	exit();
}

function appendUrlParams($url, $append) {
	if (strpos($url,'?')===false) {
		return $url.'?'.$append;
	} else {
		return $url.'&'.$append;
	}
}

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>

<h1>Mock Cetelem GateWay</h1>

<form method="POST">
<table>
<tr><td>numaut</td><td><input type=text name="numaut" value="123"> pouze pro OK</td></tr>
<tr><td>vdr</td><td><input type=text name="vdr" value="<?php echo $_POST['kodProdejce'] ?>"> pouze pro KO</td></tr>
<tr><td>numwrk</td><td><input type=text name="numwrk" value="1234"></td></tr>
<tr><td>jmeno</td><td><input type=text name="jmeno" value="Alois"></td></tr>
<tr><td>prijmeni</td><td><input type=text name="prijmeni" value="Jirasek"></td></tr>
<tr><td>splatka</td><td><input type=text name="splatka" value="2211"></td></tr>
<tr><td>numklient</td><td><input type=text name="numklient" value="<?php echo $_POST['numklient'] ?>"></td></tr>
<tr><td>obj</td><td><input type=text name="obj" value="<?php echo $_POST['obj'] ?>"></td></tr>
<tr><td><input type=submit name="OK" value="OK - Schvaleno online"> <input type=submit name="KO" value="KO - Bude posouzeno"></td></tr>
</table>
<input type=hidden name="url_back_ok" value="<?php echo $_POST['url_back_ok'] ?>">
<input type=hidden name="url_back_ko" value="<?php echo $_POST['url_back_ko'] ?>">

</form>

<?php var_dump($_POST); ?> 

</body>
</html>
