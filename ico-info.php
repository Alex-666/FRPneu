<?php 
header('Content-type: application/xml');

if (isset($_GET['ico'])) {
echo file_get_contents_curl("https://wwwinfo.mfcr.cz/cgi-bin/ares/darv_std.cgi?ico=".$_GET['ico']);
}

function file_get_contents_curl($url) {
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Устанавливаем параметр, чтобы curl возвращал данные, вместо того, чтобы выводить их в браузер.
	curl_setopt($ch, CURLOPT_URL, $url);

	$data = curl_exec($ch);
	curl_close($ch);

	return $data;
}

?>