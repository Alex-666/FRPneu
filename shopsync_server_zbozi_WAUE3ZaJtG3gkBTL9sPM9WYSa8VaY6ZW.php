<?php $set_disableinclude=true; ?><?php

class Encryption { 
    var $skey = cryptcode;
 
    public function safe_b64encode($string) { 
        $data = base64_encode($string); 
        $data = str_replace(array('+','/','='),array('-','_',''),$data); 
        return $data; 
    } 
 
    public function safe_b64decode($string) { 
        $data = str_replace(array('-','_'),array('+','/'),$string); 
        $mod4 = strlen($data) % 4; 
        if ($mod4) { 
            $data .= substr('====', $mod4); 
        } 
        return base64_decode($data); 
    } 
 
    public  function encode($value){  
        if(!$value){return false;} 
        $text = $value; 
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB); 
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND); 
        $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->skey, $text, MCRYPT_MODE_ECB, $iv); 
        return trim($this->safe_b64encode($crypttext));  
    } 
 
    public function decode($value){ 
        if(!$value){return false;} 
        $crypttext = $this->safe_b64decode($value);  
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB); 
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND); 
        $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->skey, $crypttext, MCRYPT_MODE_ECB, $iv); 
        return trim($decrypttext); 
    } 
} 



function read_batch_query(){
	$enc=new Encryption();
$qry=$enc->decode($_POST["data"]);
if(sha1(cryptcode.$qry)!=$_POST["check"]){die("checksum doesnt match!");}
	
return unserialize($qry);
}




?><?php
define("db_server",'md16.wedos.net');
define("db_user",'a185459_frp3');
define("db_pass",'r6KnE5VW');
define("db_name",'d185459_frp3');
define("db_prefix",'oc_');
define("ftp_server",'185459.w59.wedos.net');
define("ftp_user",'w185459_test');
define("ftp_pass",'cLNnKTqt');
define("ftp_passive",'True');
define("ftp_mode",'ftp');
define("set_url",'http://test.frpneu.eu');
define("set_sw",'2');
define("img_ftp_dir",'image/data/');
define("img_dir",'C:/Stormware/Dokumenty/Obrazky/Obrázky');
define("temp_dir",'C:/shopsync_temp');
define("att_dir",'');
define("ftp_main_dir",'');
define("set_ico",'27458920');
define("set_homecurrency",'CZK');
define("set_vat",'21');
define("set_vatlow",'15');
define("set_vatthird",'');
define("set_usevat",true);
define("set_stockname",'');
define("set_stockcode",'');
define("set_groupname",'');
define("set_groupcode",'');
define("set_pohoda_db",'Driver={SQL Server Native Client 10.0};Server=127.0.0.1;Database=StwPh_27458920_201801;Trusted_Connection=yes;MARS_Connection=yes');
define("set_apppath",'C:\stormware\Pohoda.exe');
define("set_agenda",'');
define("set_year",'');
define("filter_ord",'where o.order_status_id=\'1\' and o.date_added>now()-interval 14 day ');
define("set_bridgepath",'shopsync_server_WAUE3ZaJtG3gkBTL9sPM9WYSa8VaY6ZW.php');
define("set_usebridge",'false');
define("set_bridgebatch",'true');
define("set_fnkey",'WAUE3ZaJtG3gkBTL9sPM9WYSa8VaY6ZW');
define("set_dbfile",'StwPh_27458920_201801');
define("set_pricegroup1",'');
define("set_pricegroup2",'');
define("set_pricegroup3",'');
define("set_shoppergroup1",'');
define("set_shoppergroup2",'');
define("set_shoppergroup3",'');
define("set_pricegroup4",'');
define("set_pricegroup5",'');
define("set_pricegroup6",'');
define("set_shoppergroup4",'');
define("set_shoppergroup5",'');
define("set_shoppergroup6",'');
define("filter_stock",'Disky;Doplňky;Nákladní');
define("lang",'2');
define("taxclass",'11');
define("taxclasslow",'12');
define("taxclassthird",'');
define("taxclassnull",'');
define("shopid",'');
define("rootcat",'');
define("state1",'0');
define("state2",'0');
define("state3",'2');
define("state4",'3');
define("state1_1",'');
define("state2_1",'');
define("state3_1",'');
define("state4_1",'');
define("state1_2",'');
define("state2_2",'');
define("state3_2",'');
define("state4_2",'');
define("sendmail1",'False');
define("sendmail2",'False');
define("sendmail1_1",'False');
define("sendmail2_1",'False');
define("sendmail1_2",'False');
define("sendmail2_2",'False');
define("autoscript",'scripts/xxauto_pohoda_opencart.php');
define("sw_user",'Admin');
define("sw_pass",'benq');
?>
<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);


if($set_disableinclude!=true){
require_once("config.php");
}

//define("lang", 3); 
//define("tax_class",1);
define("shopid",1);
define("cryptcode", substr(str_pad(db_pass,32),0,32)); 
define("bridge_url", set_url."/".set_bridgepath); 
define("cat_group",1);

if($set_disableinclude!=true){
include_once("mysqlbridge/client.php");
}
if(set_usebridge=="false"){
    $dbaddr=explode(":",db_server,2);
    if(count($dbaddr)==1){
        $GLOBALS["mysql_link"]=mysqli_connect(db_server,db_user,db_pass) or die("Chyba pri pripojovani k DB.");
    }else{
        $GLOBALS["mysql_link"]=mysqli_connect($dbaddr[0],db_user,db_pass,"",$dbaddr[1]) or die("Chyba pri pripojovani k DB.");
    }
mysqli_select_db($GLOBALS["mysql_link"],db_name) or die("Spatny nazev databaze.");
//qry("set names cp1250");
if($set_disableinclude!=true){
    echo("DB pripojena. \n");
}
}

define("debug",false);


qry("SET NAMES utf8");
qry("SET SESSION sql_mode = ''");
//mysql_query("SET CHARACTER_SET utf8");
function md53($s){
    $out= base64_encode(hash_hmac("md5",$s,"shopsync",true));
    $out=strtoupper(str_replace(array('+','/','=','_'),array('0','0','0','0'),$out)); 
    return $out;
}

function getSQLcmd($data){
    $str="";
    foreach($data as $key=>$val){
        $val=sqlSafe($val);
        if($str!=""){$str.=",";}
        $str.="$key='$val'";
    }
    return $str;
}

function transferData($table, $guidfield, $guid, $data, $idfield){
    $res=qry("select * from ".db_prefix."$table where $guidfield='$guid'");

    if(numrows($res)==0){
        qry("insert into ".db_prefix."$table set ".getSQLcmd($data).",date_add=now(),  $guidfield='$guid'");
    }else{
        qry("update ".db_prefix."$table set ".getSQLcmd($data).", date_upd=now() where $guidfield='$guid'");
    }
    $res=qry("select * from ".db_prefix."$table where $guidfield='$guid'");
    return res($res,0,$idfield);


}
function getSqlUid(){
    preg_match('/Uid=(.*?)(;|$)/',set_pohoda_db,$match);
	return $match[1];
}

function getSqlPwd(){
    preg_match('/Pwd=(.*?)(;|$)/',set_pohoda_db,$match);
	return $match[1];
}


function convertRes($s){
    $s=autoutf($s);
    $s=sqlSafe($s);
    return $s;

}

function getSQLDate($time){
    if(set_sw==0){
        return "#".date("Y-m-d",$time)."#";
    }else{
        return "'".date("Y-m-d",$time)."'";
    }

}


function ftp_upload($src,$dest){
    if ($src!=null && file_exists($src) && is_file($src)) {

		$pokus=0;
		while(true){	
			
			$pokus++;
			if($GLOBALS["curlhandle_ftp"]==null || time() - $GLOBALS["curlhandle_ftp_last"]>360){
				echo "Pripojovani k FTP \n";
				$ch = curl_init(); 
				$GLOBALS["curlhandle_ftp"]=$ch;
			}else{
				$ch=$GLOBALS["curlhandle_ftp"];
			}



			$localfile = $src;
			$fp = fopen($localfile, 'r');
			$ftpserv=explode(":",ftp_server);
			curl_setopt($ch, CURLOPT_URL, "ftp://".$ftpserv[0]."/".$dest);
			if(count($ftpserv)>1){
				curl_setopt($ch, CURLOPT_PORT, $ftpserv[1]); 
			}
			if(ftp_mode=="ftps"){
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);  
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);  
				curl_setopt($ch, CURLOPT_FTP_SSL, CURLFTPSSL_TRY);  
				//   curl_setopt($ch, CURLOPT_SSLVERSION, 3); 
				
			}
			curl_setopt($ch, CURLOPT_USERPWD, ftp_user.":".ftp_pass);
			curl_setopt($ch, CURLOPT_UPLOAD, 1);
			curl_setopt($ch, CURLOPT_INFILE, $fp);
			curl_setopt($ch, CURLOPT_INFILESIZE, filesize($localfile));
			curl_setopt($ch, CURLOPT_FTP_CREATE_MISSING_DIRS , true);
			if(ftp_passive=="False"){
				curl_setopt($ch, CURLOPT_FTP_USE_EPRT, true);
			}
			curl_exec ($ch);
			$error_no = curl_errno($ch);
			//   curl_close ($ch);

			$GLOBALS["curlhandle_ftp_last"]=time(); 

			fclose($fp);
			
			if ($error_no == 0 || $error_no==18) {
				//  $error = 'File uploaded succesfully.';
				$GLOBALS["curlhandle_ftp_last"]=time();
				
				return true;break;
			} else {
				echo "ERR: chyba pri nahravani souboru na FTP, kod: $error_no \n";
				$GLOBALS["curlhandle_ftp"]=null;
				sleep(5);
				if($pokus>5){
					
					break;
					
				}
			}

			
		}

		return false;

    }else{
        echo "Soubor $src neexistuje. \n";  
        return false;
    }
}

function getLastUpd($table){
    $res=qry("select * from shopsync_last where `table`='$table'");
    if(numrows($res)==0){
        qry("insert into shopsync_last set `table`='$table'");
    }
    $GLOBALS["timestart"]=date('Y-m-d H:i:s');return @res($res,0,"dt");
}

function setLastUpd($table){
    qry("update shopsync_last set  dt='".$GLOBALS["timestart"]."' where `table`='$table'");
}


function md52($s){
    $out= base64_encode(hash_hmac("md5",$s,"shopsync",true));
    $out=str_replace(array('+','/','='),array('_','_',''),$out); 
    return $out;
}



function PerformSaveColsMode($arr, $data, $id, $table="postmeta", $idreccol="meta_id", $idcol="post_id", $keycol="meta_key", $valcol="meta_value"){
foreach($arr as $key=>$datakey){

    
    
    
    $val=$arr[$key];
if(substr($val,0,1)=="#"){$val=substr($val,1,strlen($val));}else{
$val=sqlSafe($data[$datakey]);		
}

//echo $key.":".$val."\n";

$idrec=@res(qry("select $idreccol from ".db_prefix."$table where $idcol='$id' and $keycol='$key' limit 1"),0,"$idreccol");

if($idrec==null){
qry("insert into ".db_prefix."$table ($idcol,$keycol,$valcol) values ('$id','$key','$val')");
}else{
qry("update ".db_prefix."$table set $valcol='$val' where $idreccol='$idrec'");	
}

//echo "update ".db_prefix."$table set $valcol='$val' where $idreccol='$idrec'\n\n";

}	
}

function ReadCols($id, $table="postmeta", $idreccol="meta_id", $idcol="post_id", $keycol="meta_key", $valcol="meta_value"){
    $res=qry("select $keycol, $valcol from ".db_prefix."$table where $idcol='$id'");
    
    $out=array();
    for($i=0;$i<numrows($res);$i++){
        $out[res($res,$i,$keycol)]=res($res,$i,$valcol);
    }
    
    return $out;
}


function roundPrice($price){
return ceil($price);	
}

function qSafe($input)
{
	return str_replace(array('\'','&amp;'),array('"','&'),$input);
}

function xmlSafe($input)
{
	return str_replace(array('&','\'','"','<','>'),array('&amp;','','','',''),$input);
}

function sqlSafe($input){
	return (str_replace("'","Â´",$input));	
	//return mysql_escape_string(htmlspecialchars($input));	
	
}


function maxLen($str,$len){
return substr($str,0,$len);	
}

function sqlf($result){
	
if(set_usebridge=="false"){

	$row = mysqli_fetch_row($result);
	if($row === FALSE) return FALSE;
	
	for ($i = 0; $i < mysqli_num_fields($result); $i++) {
		$meta = mysqli_fetch_field($result, $i);//chgyba
		$data[$meta ->table . '.' . $meta->name] = $row[$i];
	}
	return $data;
}else{
mysql_bridge_sqlf($result);
}
}

function qry($query){
	//echo $query."\r\n";
	//file_put_contents("./lastquery.sql",$query);
	$GLOBALS["lastquery"]=$query;
	if(set_usebridge=="false"){
        $res=mysqli_query($GLOBALS["mysql_link"],$query) or die(mysqli_error($GLOBALS["mysql_link"])." ".$query);	
	}else{
	$res=mysql_bridge_query($query);	
	}
	return $res;
}

function objecthash($obj){
    
    
    $ser=print_r($obj,true);
    // echo $ser;
    return md53($ser);
}

function mysqli_result($res,$row=0,$col=0){ 
    $numrows = mysqli_num_rows($res); 
    if ($numrows && $row <= ($numrows-1) && $row >=0){
        $fields=array();
        $table="";
        $i=0;
        $tablenames=array();
        
        
        //  echo objecthash($res)."\n";
        
        if($GLOBALS["mrf".objecthash($res)]==null || 1==1){
            mysqli_field_seek($res,0);
            while ($meta = mysqli_fetch_field($res)) {
                $fields[$i]=$meta->name;
                $tablenames[$i]=$meta->table;
                $i++;
            }
            $GLOBALS["mrf".objecthash($res)]=array($fields,$tablenames);
        }else{
            $fields=$GLOBALS["mrf".objecthash($res)][0];
            $tablenames=$GLOBALS["mrf".objecthash($res)][1];
        }

        $resrow=array();
        mysqli_data_seek($res,$row);
        $rrow = mysqli_fetch_array($res);
        //print_r($rrow);
        for($ix=0;$ix<count($fields);$ix++){
            $field=$fields[$ix];
            $table=$tablenames[$ix];
			 $resrow[$ix] = $rrow[$ix];
            $resrow[$field] = $rrow[$ix];
            $resrow[$table . '.' . $field] = $rrow[$ix];
            $i++;
        }
        // print_r($resrow);
        return $resrow[$col];
    }
    
    return false; 
    
}

function res($res,$row,$field=0){
    

    
if(set_usebridge=="false"){



  //  if( mysql_result($res,$row,$field)===false){echo $field;}
return mysqli_result($res,$row,$field);	
}else{
return mysql_bridge_result($res,$row,$field);		
}	
}

function numrows($res){
if(set_usebridge=="false"){
return mysqli_num_rows($res);	
}else{
return mysql_bridge_numrows($res);		
}	
}

function lastid(){
if(set_usebridge=="false"){
	return mysqli_insert_id($GLOBALS["mysql_link"]);	
}else{
return mysql_bridge_lastid($res);		
}	
}

function err(){
if(set_usebridge=="false"){
return mysqli_error();	
}else{
return "ERR ".$GLOBALS["lastquery"];		
}		
}




function getUpdateCodes($arr, $data){
foreach($arr as $col=>$datakey){
	if($out!=null){$out.=", ";}
$out.= "$col = '".qSafe($data[$datakey])."'";	
}	
	
return $out;
}

function getInsertCodes($arr, $data){
foreach($arr as $col=>$datakey){
		if($cols!=null){$cols.=", ";$vals.=", ";}
$cols.=$col;
$vals.="'".qSafe($data[$datakey])."'";
}	

$out=array();
$out["cols"]=$cols;
$out["vals"]=$vals;
	
return $out;
}


function GetBasename($str,$table, $field, $cond=null){
   $basename=basestring_url($str);
  while(true){ $i++;
		$res=qry("SELECT * FROM $table WHERE $field='$basename'  $cond ");
		if(numrows($res)==0){break;}else{$basename=basestring_url($str)."-".GeneratePass(3);} 
		if($i>4){break;}
  }
  	
  return $basename;      
}

function basestring_url($str){
	//$str=autoUTF($str);
	//$str=strtolower(trim($str));
	$str=cs_utf2ascii($str);
	
	$str = strtr($str, " /.,`Â´Â´!@#%^&*()<>?:'{}|+",  //,
	 "-------------------------"); //4
	$str=str_replace("\"","",$str);
	$str=str_replace("\'","",$str);
	$str=strtolower($str);
	//$str=str_replace(" ","",$str);
	
	$str=urlencode($str);
	$str=preg_replace('/\%[0-9a-fA-F]{2,5}/',"-",$str);
	$str=preg_replace('/\-+/',"-",$str);
    $str=preg_replace('/^\-+/',"",$str);
    $str=preg_replace('/\-+$/',"",$str);
	 return $str;
}


// WINDOWS-1250 to ASCII for diacritic chars 
function cs_win2ascii($s) 
{ 
    return strtr($s, "\xe1\xe4\xe8\xef\xe9\xec\xed\xbe\xe5\xf2\xf3\xf6\xf5\xf4\xf8\xe0\x9a\x9d\xfa\xf9\xfc\xfb\xfd\x9e\xc1\xc4\xc8\xcf\xc9\xcc\xcd\xbc\xc5\xd2\xd3\xd6\xd5\xd4\xd8\xc0\x8a\x8d\xda\xd9\xdc\xdb\xdd\x8e", "aacdeeillnoooorrstuuuuyzAACDEEILLNOOOORRSTUUUUYZ"); 

} 


// ISO-8859-2 to ASCII for diacritic chars 
function cs_iso2ascii($s) 
{ 
    return strtr($s, "\xe1\xe4\xe8\xef\xe9\xec\xed\xb5\xe5\xf2\xf3\xf6\xf5\xf4\xf8\xe0\xb9\xbb\xfa\xf9\xfc\xfb\xfd\xbe\xc1\xc4\xc8\xcf\xc9\xcc\xcd\xa5\xc5\xd2\xd3\xd6\xd5\xd4\xd8\xc0\xa9\xab\xda\xd9\xdc\xdb\xdd\xae", "aacdeeillnoooorrstuuuuyzAACDEEILLNOOOORRSTUUUUYZ"); 

} 


// UTF-8 to ASCII for diacritic chars 
function cs_utf2ascii($s) 
{ 
    static $tbl = array("\xc3\xa1"=>"a","\xc3\xa4"=>"a","\xc4\x8d"=>"c","\xc4\x8f"=>"d","\xc3\xa9"=>"e","\xc4\x9b"=>"e","\xc3\xad"=>"i","\xc4\xbe"=>"l","\xc4\xba"=>"l","\xc5\x88"=>"n","\xc3\xb3"=>"o","\xc3\xb6"=>"o","\xc5\x91"=>"o","\xc3\xb4"=>"o","\xc5\x99"=>"r","\xc5\x95"=>"r","\xc5\xa1"=>"s","\xc5\xa5"=>"t","\xc3\xba"=>"u","\xc5\xaf"=>"u","\xc3\xbc"=>"u","\xc5\xb1"=>"u","\xc3\xbd"=>"y","\xc5\xbe"=>"z","\xc3\x81"=>"A","\xc3\x84"=>"A","\xc4\x8c"=>"C","\xc4\x8e"=>"D","\xc3\x89"=>"E","\xc4\x9a"=>"E","\xc3\x8d"=>"I","\xc4\xbd"=>"L","\xc4\xb9"=>"L","\xc5\x87"=>"N","\xc3\x93"=>"O","\xc3\x96"=>"O","\xc5\x90"=>"O","\xc3\x94"=>"O","\xc5\x98"=>"R","\xc5\x94"=>"R","\xc5\xa0"=>"S","\xc5\xa4"=>"T","\xc3\x9a"=>"U","\xc5\xae"=>"U","\xc3\x9c"=>"U","\xc5\xb0"=>"U","\xc3\x9d"=>"Y","\xc5\xbd"=>"Z"); 
    return strtr($s, $tbl); 
} 


  function mb_strtr($str,$map,$enc){
	$out="";
	$strLn=mb_strlen($str,$enc);
	$maxKeyLn=1;
	foreach($map as $key=>$val){
		$keyLn=mb_strlen($key,$enc);
		if($keyLn>$maxKeyLn){
			$maxKeyLn=$keyLn;
		}
	}
	for($offset=0; $offset<$strLn; ){
		for($ln=$maxKeyLn; $ln>=1; $ln--){
			$cmp=mb_substr($str,$offset,$ln,$enc);
			if(isset($map[$cmp])){
				$out.=$map[$cmp];
				$offset+=$ln;
				continue 2;
			}
		}
		$out.=mb_substr($str,$offset,1,$enc);
		$offset++;
	}
	return $out;
    }

function GeneratePass ($length = 8)
{
  $password = "";
  $possible = "abcdefghijklmnopqrstuvwxyz"; 
  $i = 0; 
  while ($i < $length) { 
    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
    if (!strstr($password, $char)) { 
      $password .= $char;
      $i++;
    }}return $password;
}


function autoUTF($s) 
{ 
	// detect UTF-8 
	if (preg_match('#[\x80-\x{1FF}\x{2000}-\x{3FFF}]#u', $s)) 
		return $s; 

	// detect WINDOWS-1250 
	
		return strtr($s, array("\x80"=>"\xe2\x82\xac","\x81"=>"","\x82"=>"\xe2\x80\x9a","\x83"=>"","\x84"=>"\xe2\x80\x9e","\x85"=>"\xe2\x80\xa6","\x86"=>"\xe2\x80\xa0","\x87"=>"\xe2\x80\xa1","\x88"=>"","\x89"=>"\xe2\x80\xb0","\x8a"=>"\xc5\xa0","\x8b"=>"\xe2\x80\xb9","\x8c"=>"\xc5\x9a","\x8d"=>"\xc5\xa4","\x8e"=>"\xc5\xbd","\x8f"=>"\xc5\xb9","\x90"=>"","\x91"=>"\xe2\x80\x98","\x92"=>"\xe2\x80\x99","\x93"=>"\xe2\x80\x9c","\x94"=>"\xe2\x80\x9d","\x95"=>"\xe2\x80\xa2","\x96"=>"\xe2\x80\x93","\x97"=>"\xe2\x80\x94","\x98"=>"","\x99"=>"\xe2\x84\xa2","\x9a"=>"\xc5\xa1","\x9b"=>"\xe2\x80\xba","\x9c"=>"\xc5\x9b","\x9d"=>"\xc5\xa5","\x9e"=>"\xc5\xbe","\x9f"=>"\xc5\xba","\xa0"=>"\xc2\xa0","\xa1"=>"\xcb\x87","\xa2"=>"\xcb\x98","\xa3"=>"\xc5\x81","\xa4"=>"\xc2\xa4","\xa5"=>"\xc4\x84","\xa6"=>"\xc2\xa6","\xa7"=>"\xc2\xa7","\xa8"=>"\xc2\xa8","\xa9"=>"\xc2\xa9","\xaa"=>"\xc5\x9e","\xab"=>"\xc2\xab","\xac"=>"\xc2\xac","\xad"=>"\xc2\xad","\xae"=>"\xc2\xae","\xaf"=>"\xc5\xbb","\xb0"=>"\xc2\xb0","\xb1"=>"\xc2\xb1","\xb2"=>"\xcb\x9b","\xb3"=>"\xc5\x82","\xb4"=>"\xc2\xb4","\xb5"=>"\xc2\xb5","\xb6"=>"\xc2\xb6","\xb7"=>"\xc2\xb7","\xb8"=>"\xc2\xb8","\xb9"=>"\xc4\x85","\xba"=>"\xc5\x9f","\xbb"=>"\xc2\xbb","\xbc"=>"\xc4\xbd","\xbd"=>"\xcb\x9d","\xbe"=>"\xc4\xbe","\xbf"=>"\xc5\xbc","\xc0"=>"\xc5\x94","\xc1"=>"\xc3\x81","\xc2"=>"\xc3\x82","\xc3"=>"\xc4\x82","\xc4"=>"\xc3\x84","\xc5"=>"\xc4\xb9","\xc6"=>"\xc4\x86","\xc7"=>"\xc3\x87","\xc8"=>"\xc4\x8c","\xc9"=>"\xc3\x89","\xca"=>"\xc4\x98","\xcb"=>"\xc3\x8b","\xcc"=>"\xc4\x9a","\xcd"=>"\xc3\x8d","\xce"=>"\xc3\x8e","\xcf"=>"\xc4\x8e","\xd0"=>"\xc4\x90","\xd1"=>"\xc5\x83","\xd2"=>"\xc5\x87","\xd3"=>"\xc3\x93","\xd4"=>"\xc3\x94","\xd5"=>"\xc5\x90","\xd6"=>"\xc3\x96","\xd7"=>"\xc3\x97","\xd8"=>"\xc5\x98","\xd9"=>"\xc5\xae","\xda"=>"\xc3\x9a","\xdb"=>"\xc5\xb0","\xdc"=>"\xc3\x9c","\xdd"=>"\xc3\x9d","\xde"=>"\xc5\xa2","\xdf"=>"\xc3\x9f","\xe0"=>"\xc5\x95","\xe1"=>"\xc3\xa1","\xe2"=>"\xc3\xa2","\xe3"=>"\xc4\x83","\xe4"=>"\xc3\xa4","\xe5"=>"\xc4\xba","\xe6"=>"\xc4\x87","\xe7"=>"\xc3\xa7","\xe8"=>"\xc4\x8d","\xe9"=>"\xc3\xa9","\xea"=>"\xc4\x99","\xeb"=>"\xc3\xab","\xec"=>"\xc4\x9b","\xed"=>"\xc3\xad","\xee"=>"\xc3\xae","\xef"=>"\xc4\x8f","\xf0"=>"\xc4\x91","\xf1"=>"\xc5\x84","\xf2"=>"\xc5\x88","\xf3"=>"\xc3\xb3","\xf4"=>"\xc3\xb4","\xf5"=>"\xc5\x91","\xf6"=>"\xc3\xb6","\xf7"=>"\xc3\xb7","\xf8"=>"\xc5\x99","\xf9"=>"\xc5\xaf","\xfa"=>"\xc3\xba","\xfb"=>"\xc5\xb1","\xfc"=>"\xc3\xbc","\xfd"=>"\xc3\xbd","\xfe"=>"\xc5\xa3","\xff"=>"\xcb\x99"));

}

function ResizeOnCanvas($sourceFile, $targetFile, $width, $height, $nocanvas=false){



    if(file_exists($sourceFile)){
        $size = GetImageSize($sourceFile);
        

        
		if($nocanvas==false){
			$canvas_width=$width;	
			$canvas_height=$height;	
        }
        
        
        
		
		
        if($size[0]>=$size[1]){
            $ratio = $size[0]/$width;
            $height = $size[1]/$ratio;
            if($height>$canvas_height && $canvas_height!=null){
                $ratio = $height/$canvas_height;
                $height = $height/$ratio;	
                $width = $width/$ratio;	
            }
        }
        else{
            $ratio = $size[1]/$height;
            $width = $size[0]/$ratio;
            if($width>$canvas_width && $canvas_width!=null){
                $ratio = $width/$canvas_width;
                $height = $height/$ratio;	
                $width = $width/$ratio;	
            }
        }
        
        
        
        
        if($ratio<1){$width = $size[0];$height=$size[1];}
        
		
        if($nocanvas==true){
			$canvas_width=$width;	
			$canvas_height=$height;	
        }
		
        
        $type = exif_imagetype($sourceFile);
        
        
		/*	if(strpos($sourceFile,".png")==false){
        $fp = imagecreatefromjpeg($sourceFile);
        }else{
        $fp = imagecreatefrompng($sourceFile);
        }
         */
        $imgtype=0;
        if($type==IMAGETYPE_JPEG || $type==IMAGETYPE_JPEG2000){
            $fp = imagecreatefromjpeg($sourceFile);
            $imgtype="jpg";
        }elseif($type==IMAGETYPE_PNG){
            $fp = imagecreatefrompng($sourceFile);
            $imgtype="png";
        }elseif($type==IMAGETYPE_GIF){
            $fp = imagecreatefromgif($sourceFile);
            $imgtype="gif";
        }else{
            echo ("Neznamy format obrazku: $sourceFile"); return false; 
        }
        
        $white = imagecolorallocate($fp,  255, 255, 255);

        $fx = imagecreatetruecolor($width, $height);
		imagefilledrectangle($fx, 0, 0, $width, $height, $white);	
		
        if($imgtype=="gif"){
            
            
            $tmp = imagecreatetruecolor($size[0], $size[1]);
			$background = imagecolorallocate($tmp, 255, 255, 255); 
            
            imagefilledrectangle($tmp, 0, 0, $size[0], $size[1], $background);	
            
			$trans=imagecolortransparent($fp);
            
			imagecolorset($fp,$trans,255,255,255);
            
            imagecopy($tmp,$fp,0,0,0,0,$size[0],$size[1]);
            $fp=$tmp;
            
        }
        if($imgtype=="png"){
            //    imagealphablending($fp, FALSE);
            //    imagesavealpha($fp, TRUE);
            $tmp = imagecreatetruecolor($size[0], $size[1]);
			$background = imagecolorallocate($tmp, 255, 255, 255); 
            
            imagefilledrectangle($tmp, 0, 0, $size[0], $size[1], $background);	
            
			$trans=imagecolortransparent($fp);
            
			imagecolorset($fp,$trans,255,255,255);
            
            imagecopy($tmp,$fp,0,0,0,0,$size[0],$size[1]);
            $fp=$tmp;
        }
        
		$canvas = imagecreatetruecolor($canvas_width, $canvas_height);
        imagefill($canvas,0,0,imagecolorallocate($canvas,255,255,255));
        
        imagecopyresampled($fx, $fp, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
        
        
		imagecopyresampled($canvas, $fx, $canvas_width/2-$width/2, $canvas_height/2-$height/2, 0, 0, $width, $height, $width, $height);
        
        imagejpeg($canvas, $targetFile, 95);
        
        imagedestroy($fp);
        imagedestroy($fx);
        
        return true;
    }
    
	echo("Nelze zmenit velikost: ".$sourceFile);
}

function utf2win($s) 
{ 
    $s = preg_replace('#[^\x00-\x7F\xa0\xa4\xa6-\xa9\xab-\xae\xb0\xb1\xb4-\xb8\xbb\xc1\xc2\xc4\xc7\xc9\xcb\xcd\xce\xd3\xd4\xd6\xd7\xda\xdc\xdd\xdf\xe1\xe2\xe4\xe7\xe9\xeb\xed\xee\xf3\xf4\xf6\xf7\xfa\xfc\xfd\x{102}-\x{107}\x{10c}-\x{111}\x{118}-\x{11b}\x{139}\x{13a}\x{13d}\x{13e}\x{141}-\x{144}\x{147}\x{148}\x{150}\x{151}\x{154}\x{155}\x{158}-\x{15b}\x{15e}-\x{165}\x{16e}-\x{171}\x{179}-\x{17e}\x{2c7}\x{2d8}\x{2d9}\x{2db}\x{2dd}\x{2013}\x{2014}\x{2018}-\x{201a}\x{201c}-\x{201e}\x{2020}-\x{2022}\x{2026}\x{2030}\x{2039}\x{203a}\x{20ac}\x{2122}]#u', '', $s); 
    static $tbl = array("\xe2\x82\xac"=>"\x80","\xe2\x80\x9a"=>"\x82","\xe2\x80\x9e"=>"\x84","\xe2\x80\xa6"=>"\x85","\xe2\x80\xa0"=>"\x86","\xe2\x80\xa1"=>"\x87","\xe2\x80\xb0"=>"\x89","\xc5\xa0"=>"\x8a","\xe2\x80\xb9"=>"\x8b","\xc5\x9a"=>"\x8c","\xc5\xa4"=>"\x8d","\xc5\xbd"=>"\x8e","\xc5\xb9"=>"\x8f","\xe2\x80\x98"=>"\x91","\xe2\x80\x99"=>"\x92","\xe2\x80\x9c"=>"\x93","\xe2\x80\x9d"=>"\x94","\xe2\x80\xa2"=>"\x95","\xe2\x80\x93"=>"\x96","\xe2\x80\x94"=>"\x97","\xe2\x84\xa2"=>"\x99","\xc5\xa1"=>"\x9a","\xe2\x80\xba"=>"\x9b","\xc5\x9b"=>"\x9c","\xc5\xa5"=>"\x9d","\xc5\xbe"=>"\x9e","\xc5\xba"=>"\x9f","\xc2\xa0"=>"\xa0","\xcb\x87"=>"\xa1","\xcb\x98"=>"\xa2","\xc5\x81"=>"\xa3","\xc2\xa4"=>"\xa4","\xc4\x84"=>"\xa5","\xc2\xa6"=>"\xa6","\xc2\xa7"=>"\xa7","\xc2\xa8"=>"\xa8","\xc2\xa9"=>"\xa9","\xc5\x9e"=>"\xaa","\xc2\xab"=>"\xab","\xc2\xac"=>"\xac","\xc2\xad"=>"\xad","\xc2\xae"=>"\xae","\xc5\xbb"=>"\xaf","\xc2\xb0"=>"\xb0","\xc2\xb1"=>"\xb1","\xcb\x9b"=>"\xb2","\xc5\x82"=>"\xb3","\xc2\xb4"=>"\xb4","\xc2\xb5"=>"\xb5","\xc2\xb6"=>"\xb6","\xc2\xb7"=>"\xb7","\xc2\xb8"=>"\xb8","\xc4\x85"=>"\xb9","\xc5\x9f"=>"\xba","\xc2\xbb"=>"\xbb","\xc4\xbd"=>"\xbc","\xcb\x9d"=>"\xbd","\xc4\xbe"=>"\xbe","\xc5\xbc"=>"\xbf","\xc5\x94"=>"\xc0","\xc3\x81"=>"\xc1","\xc3\x82"=>"\xc2","\xc4\x82"=>"\xc3","\xc3\x84"=>"\xc4","\xc4\xb9"=>"\xc5","\xc4\x86"=>"\xc6","\xc3\x87"=>"\xc7","\xc4\x8c"=>"\xc8","\xc3\x89"=>"\xc9","\xc4\x98"=>"\xca","\xc3\x8b"=>"\xcb","\xc4\x9a"=>"\xcc","\xc3\x8d"=>"\xcd","\xc3\x8e"=>"\xce","\xc4\x8e"=>"\xcf","\xc4\x90"=>"\xd0","\xc5\x83"=>"\xd1","\xc5\x87"=>"\xd2","\xc3\x93"=>"\xd3","\xc3\x94"=>"\xd4","\xc5\x90"=>"\xd5","\xc3\x96"=>"\xd6","\xc3\x97"=>"\xd7","\xc5\x98"=>"\xd8","\xc5\xae"=>"\xd9","\xc3\x9a"=>"\xda","\xc5\xb0"=>"\xdb","\xc3\x9c"=>"\xdc","\xc3\x9d"=>"\xdd","\xc5\xa2"=>"\xde","\xc3\x9f"=>"\xdf","\xc5\x95"=>"\xe0","\xc3\xa1"=>"\xe1","\xc3\xa2"=>"\xe2","\xc4\x83"=>"\xe3","\xc3\xa4"=>"\xe4","\xc4\xba"=>"\xe5","\xc4\x87"=>"\xe6","\xc3\xa7"=>"\xe7","\xc4\x8d"=>"\xe8","\xc3\xa9"=>"\xe9","\xc4\x99"=>"\xea","\xc3\xab"=>"\xeb","\xc4\x9b"=>"\xec","\xc3\xad"=>"\xed","\xc3\xae"=>"\xee","\xc4\x8f"=>"\xef","\xc4\x91"=>"\xf0","\xc5\x84"=>"\xf1","\xc5\x88"=>"\xf2","\xc3\xb3"=>"\xf3","\xc3\xb4"=>"\xf4","\xc5\x91"=>"\xf5","\xc3\xb6"=>"\xf6","\xc3\xb7"=>"\xf7","\xc5\x99"=>"\xf8","\xc5\xaf"=>"\xf9","\xc3\xba"=>"\xfa","\xc5\xb1"=>"\xfb","\xc3\xbc"=>"\xfc","\xc3\xbd"=>"\xfd","\xc5\xa3"=>"\xfe","\xcb\x99"=>"\xff"); 
    return strtr($s, $tbl); 
} 


?>
<?php


if($set_disableinclude==true && set_bridgebatch=="true"){
$data=read_batch_query();

}

$processed=array();
$processed_prices=array();
$processed_options=array();
$map=array(
	"sku" => "code",
	"model" => "code",
	"quantity" => "count",
	"price" => "price_novat", 
	"pohoda_id" => "id", 
	"ean" => "EAN", 
	"pohoda_storage"=>"storage", 
	"upc" => "PLU" , 
	"pohoda_cat"=>"storage_name"
	);


$map2=array(
	"name" => "name",
	"description" => "desc2",
	"meta_description" => "desc1"
	);
	
		function getcat($namepath){



	$namepath=sqlSafe($namepath);	


	$orig_namepath=$namepath;
	
	if($namepath[0]=="/"){$namepath=mb_substr($namepath,1);}
	$namepath=mb_substr($namepath,mb_strpos($namepath,"/"));
	
	//echo "a".$orig_namepath."\n";
	
	
	$cat=qry("select category_id from ".db_prefix."category where pohoda_id like '$orig_namepath'");
		

	if(numrows($cat)==0){
		$name_arr=explode("/",$orig_namepath);
		$parent_name_arr=$name_arr;
		$parent_name_arr=array_splice($parent_name_arr,0,count($parent_name_arr)-1,null);
		$parent_name=implode("/",$parent_name_arr);
		
		
		if(count($parent_name_arr)!=0){
			$res=qry("select category_id from ".db_prefix."category where pohoda_id like '$parent_name' ");
			
			if(numrows($res)==0 && $namepath!=""){
				$parent_id=getcat($parent_name);
			}else{
				$parent_id=intval(@res($res,0,"category_id"));	
			}	
			
		
			qry("insert into ".db_prefix."category (pohoda_id, status, sort_order, parent_id) 
				values 
			('".$orig_namepath."','1',0,'$parent_id') ");		
				
		}else{
			qry("insert into ".db_prefix."category (pohoda_id, status, sort_order) 
				values 
			('".$orig_namepath."','1',0) ");
			
			
		}
	//	$id=lastid();
		$id=res(qry("select category_id from ".db_prefix."category where pohoda_id like '".$orig_namepath."'"),0,"category_id");
		
			qry("insert into ".db_prefix."category_description (category_id, name, description, language_id) 
			values 
			('$id','".end($name_arr)."','','".lang."') ");

			qry("insert into ".db_prefix."category_to_store (category_id, store_id) 
			values 
			('$id',0) ");
		
		
		/*	qry("insert into ".db_prefix."url_alias (query, keyword) 
				values 
			('category_id=".$id."','".GetBasename(end($name_arr),db_prefix."url_alias","keyword")."') ");	*/
			qry("insert into ".db_prefix."seo_url (query, keyword, language_id) 
				values 
			('category_id=".$id."','".GetBasename(end($name_arr),db_prefix."seo_url","keyword")."',2) ");	
			qry("insert into ".db_prefix."seo_url (query, keyword, language_id) 
				values 
			('category_id=".$id."','".GetBasename(end($name_arr),db_prefix."seo_url","keyword")."',3) ");	
				qry("insert into ".db_prefix."seo_url (query, keyword, language_id) 
				values 
			('category_id=".$id."','".GetBasename(end($name_arr),db_prefix."seo_url","keyword")."',4) ");	
		
					//path
			
			$id2=$id;
			
			qry("insert into ".db_prefix."category_path (category_id, path_id, level) 
				values 
				('$id', '$id2' ,0) ");
			
			while(true){
			
				$id2=@res(qry("select parent_id from ".db_prefix."category where category_id='".$id2."'"),0,"parent_id");
		
			//	echo $id2;
		
				if($id2==null || $id2==0){break;}else{
			//	echo "a";
				qry("insert into ".db_prefix."category_path (category_id, path_id, level) 
				values 
				('$id', '$id2' ,0) ");
				}
			}
			
			
			$res2=qry("select category_id, path_id from ".db_prefix."category_path where category_id='$id' order by path_id asc");
			for($x=0;$x<numrows($res2);$x++){
		//	echo "X".res($res2,$x,"category_id")."\n";
			qry("update ".db_prefix."category_path set level='$x' where category_id='".res($res2,$x,"category_id")."' and path_id='".res($res2,$x,"path_id")."' ");
			}
			
			
			
			//x
		
		
		}
		
	$cat=qry("select category_id from ".db_prefix."category where pohoda_id like '$orig_namepath'");
		
	
	return intval(@res($cat,0,"category_id"));
}	
	
function gettaxclass($vat){
if(round($vat)==set_vatlow){
return taxclasslow;
}else{
return taxclass;
}
}
	
$prodi=0;

$langs=array();
   $langs_res=qry("select * from ".db_prefix."language ");
   for($a=0;$a<numrows($langs_res);$a++){
       $langs[]=res($langs_res,$a,"language_id");   
   }




foreach($data as $product){

    if(filter_stock=="" || count(array_diff((explode("/",utf2win($product["storage_name"]))),explode(";",filter_stock)))>0){
$prodi++;


if($prodi%100==0){
echo ("$prodi \r\n");	
}

if(array_search($product["code"],$processed)===false){
$processed_options[$product["code"]]=array();
$processed_prices[$product["code"]]=$product["price"];
}


$test2 =qry("select product_id from ".db_prefix."product where sku='".$product["code"]."' and pohoda_id!='".$product["id"]."' and pohoda_id is not null limit 1 ");

if(numrows($test2)>0){
    qry("update ".db_prefix."product set status=0 where pohoda_id='".$product["id"]."' ");
}else{


	$test =qry("select product_id from ".db_prefix."product where sku='".$product["code"]."' limit 1 ");
	
    $prod_res=qry("select manufacturer_id from ".db_prefix."manufacturer where name like '".$product["producer"]."'");
    
    if( numrows($prod_res)==0 && $product["producer"]!=null){
        qry("insert into ".db_prefix."manufacturer (name) values ('".$product["producer"]."')");
    }
    $prod_res=qry("select manufacturer_id from ".db_prefix."manufacturer where name like '".$product["producer"]."'");
    

    


	if( numrows($test)==0){
		
		$ins=getInsertCodes($map,$product);
		
		
		qry("
			insert into ".db_prefix."product 
			(".$ins["cols"].",manufacturer_id, status, tax_class_id)
			values
			(".$ins["vals"].",'".@res($prod_res,0,"manufacturer_id")."', 1, '".gettaxclass($product["vat"])."')
			");


        //	$id=lastid();
		$id=res(qry("select product_id from ".db_prefix."product where sku='".$product["code"]."'"),0,"product_id");
        

        $ins2=getInsertCodes($map2,$product);




		qry("
			insert into ".db_prefix."product_description 
			(product_id,language_id,".$ins2["cols"].")
			values
			($id,'".lang."',".$ins2["vals"].")
			");
        
        


		qry("delete from ".db_prefix."product_to_category where product_id='$id'");		

        $cat_id=getcat($product["storage_name"]);

        if($cat_id!=null){
            qry("
			insert into ".db_prefix."product_to_category 
			(product_id, category_id)
			values
			('$id', '$cat_id')
			");
        }



		qry("
			insert into ".db_prefix."product_to_store 
			(product_id, store_id)
			values
			('$id', 0)
			");

	}else{

	//	echo "a\n";
        $id=res(qry("select product_id from ".db_prefix."product where sku='".$product["code"]."' limit 1"),0,"product_id");


	//	if(array_search($product["code"],$processed)==false){
            $processed_prices[$product["code"]]=$product["price"];
            $upd=getUpdateCodes($map,$product);



            qry("
		update ".db_prefix."product set ".$upd.", manufacturer_id='".@res($prod_res,0,"manufacturer_id")."', tax_class_id='".gettaxclass($product["vat"])."', status=1 where product_id='$id'
		"); 
	/*	}else{
            qry("
		update ".db_prefix."product set quantity=quantity+'".$product["count"]."' where product_id='$id'
		"); 
		} */

		if(strlen($product["id"])>7 && substr($product["id"],0,7)=="pneub2b"){
			//nic
		}else{

			if(trim($product["desc2"])!=""){
				$upd2=getUpdateCodes($map2,$product);
				qry("
		    update ".db_prefix."product_description set ".$upd2." where product_id='$id' and language_id='".lang."'  
	        "); 
			}else{
				qry("
		    update ".db_prefix."product_description set name='".sqlSafe($product["name"])."' where product_id='$id' and language_id='".lang."'  
	        "); 
			}
		}

        
        
        
		qry("delete from ".db_prefix."product_to_category where product_id='$id'");		

        $cat_id=getcat($product["storage_name"]);

        if($cat_id!=null){
            qry("
			insert into ".db_prefix."product_to_category 
			(product_id, category_id)
			values
			('$id', '$cat_id')
			");
        }



        

		
	}
	

    $test_urlalias=qry("select * from ".db_prefix."seo_url where query='product_id=".$id."'");
    if(numrows($test_urlalias)==0){
        qry("insert into ".db_prefix."seo_url (query, keyword, language_id) 
				values 
			    ('product_id=".$id."','".GetBasename($product["name"],db_prefix."seo_url","keyword")."',2) ");	
	        qry("insert into ".db_prefix."seo_url (query, keyword, language_id) 
				values 
			    ('product_id=".$id."','".GetBasename($product["name"],db_prefix."seo_url","keyword")."',3) ");	
	        qry("insert into ".db_prefix."seo_url (query, keyword, language_id) 
				values 
			    ('product_id=".$id."','".GetBasename($product["name"],db_prefix."seo_url","keyword")."',4) ");	
    }


    //prices
    
    if(strpos($product["id"],"pneub2b")===false){
        $pgroups=array(99999);
        if($product["pricegroup"]!=null){
            foreach($product["pricegroup"] as $group=>$gprice){
                
                
                $pgroups[]=$group;
                $gtest=qry("select product_discount_id from ".db_prefix."product_discount where product_id='$id' and customer_group_id='$group' ");
                
                if(numrows($gtest)==0){
                    qry("insert into ".db_prefix."product_discount (product_id,customer_group_id, priority,quantity) values 
		('$id','$group',1,1)");
                    
                    

                }
                qry("update ".db_prefix."product_discount 
			set price='".$gprice."'
			where product_id='$id' and customer_group_id='$group' ");
            }
        }
        qry("delete from ".db_prefix."product_discount 
			where product_id='$id' and customer_group_id not in ('".implode("','",$pgroups)."') ");
        
        
    }
    //x




    
    //options
    $option_id=1;
    $opt_codes=array(9999);
    if(is_array($product["options"]) && 1==1){
        if(count($product["options"])>1){
            
            foreach($product["options"] as $option){
                
                
                $opt=@res(qry("select option_value_id from ".db_prefix."option_value_description where name='".$option["name"]."' and option_id=$option_id limit 1"),0,"option_value_id");
                
                $opt_codes[]=$option["code"];
                
                if($opt==null){
                    qry("
				insert into ".db_prefix."option_value 
						(option_id, sort_order)
						values
						('$option_id', '".intval($option["code"])."')
				");
                    $opt=lastid();
                    qry("
				insert into ".db_prefix."option_value_description 
						(option_value_id, option_id, language_id, name)
						values
						($opt, '$option_id', '".lang."', '".$option["name"]."')
				");
                }

                $p_opt=@res(qry("select product_option_id from ".db_prefix."product_option where product_id='$id' and option_id=$option_id limit 1"),0,"product_option_id");
                if($p_opt==null){
                    qry("
				insert into ".db_prefix."product_option 
						(option_id, product_id, required)
						values
						('$option_id', '".$id."', 1)
				");
                    $p_opt=lastid();
                }
                $p_opt=@res(qry("select product_option_id from ".db_prefix."product_option where product_id='$id' and option_id=$option_id limit 1"),0,"product_option_id");

                $p_opt_val=@res(qry("select product_option_value_id from ".db_prefix."product_option_value where product_id='$id' and option_id=$option_id and 
	option_value_id='$opt'  limit 1"),0,"product_option_value_id");
                
                if($p_opt_val==null){
                    
                    qry("
				insert into ".db_prefix."product_option_value 
						(option_id, product_id, option_value_id, product_option_id, sync_code, quantity, price_prefix)
						values
						('$option_id', '".$id."','$opt', '$p_opt', '".$option["code"]."','".$option["count"]."' ,'+')
				");
                    $p_opt_val=lastid();
                }
                $p_opt_val=@res(qry("select product_option_value_id from ".db_prefix."product_option_value where product_id='$id' and option_id=$option_id and option_value_id='$opt'  limit 1"),0,"product_option_value_id");

                $defprice=res(qry("select price from ".db_prefix."product where product_id='$id' limit 1 "),0,"price");
                

                
                if($option["price"]>=$defprice){
                    qry("update ".db_prefix."product_option_value 
		set quantity='".$option["count"]."', subtract='0', price='".($option["price"]-$defprice)."', price_prefix='+', sync_code='".$option["code"]."'
		where product_option_value_id='$p_opt_val'");
                }else{
                    qry("update ".db_prefix."product_option_value 
		set quantity='".$option["count"]."', subtract='0', price='".($defprice-$option["price"])."', price_prefix='-', sync_code='".$option["code"]."'
		where product_option_value_id='$p_opt_val'");
                }
                
                

            }
        }
        
        qry("delete from ".db_prefix."product_option_value where product_id='$id' and sync_code not in ('".implode("','",$opt_codes)."')");
        
        $test=qry("select * from ".db_prefix."product_option_value where product_id='$id'");
        if(numrows($test)==0){
            qry("delete from ".db_prefix."product_option where product_id='$id'");
        }
    }
    //x
    
    


    //parameters



    foreach($product["params"] as $p){
        $res=qry("select * from ".db_prefix."attribute where pohoda_id='".$p["id"]."'");
        $pid=@res($res,0,"attribute_id");

        if($pid!=null){
            
            $pval="";
            if(count($p["values"])==1){
                $pval=$p["values"][0];
                if($pval==="true"){$pval="Ano";}
                if($pval==="false"){$pval="Ne";}
                $pval=str_replace(",000000","",$pval);
                $pval=str_replace(".00","",$pval);
                $pval=str_replace(".0","",$pval);
                $pval=sqlSafe($pval." ".res($res,0,"pohoda_unit"));
            }else{
                foreach($p["values"] as $val){
                    if($pval!=""){$pval.=", ";}
                    $val=str_replace(".00","",$val);
                    $val=str_replace(".0","",$val);
                    $pval.=$val;
                }
            }

            if(numrows($res)>0 && trim($pval)!=""){
                
                foreach($langs as $lang){
                    $test=qry("select * from ".db_prefix."product_attribute where attribute_id='$pid' and language_id='".$lang."' and product_id='$id'");
                    if(numrows($test)==0){
                        qry("insert into ".db_prefix."product_attribute set product_id='$id', attribute_id='$pid', language_id='".$lang."', text='$pval' ");
                    }else{
                        qry("update ".db_prefix."product_attribute set text='$pval' where attribute_id='$pid' and language_id='".$lang."' and product_id='$id' ");
                    }
                }
            }
        }
    }


    //x

    //related
    $rels=array();
    qry("delete from ".db_prefix."product_related where product_id='$id' ");
    foreach($product["related"] as $r){
        $idrel=@res(qry("select product_id from ".db_prefix."product where pohoda_id='$r' limit 1"),0,"product_id");
        if($idrel!=null){			
			$test=qry("select * from ".db_prefix."product_related where product_id='$id' and related_id='$idrel' limit 1");
			if(numrows($test)==0){
                qry("insert into ".db_prefix."product_related 
					set	product_id='$id', related_id='$idrel',  pohoda_id1='".$product["id"]."', pohoda_id2='$r' ");
			}
        }
    }

    
    //x
    
	
	
	
    //stock status
    if($product["expedition"]!=""){
		$sres=qry("select * from ".db_prefix."stock_status where name='".$product["expedition"]."' and language_id='".lang."' limit 1");
		if(numrows($sres)==0){
            qry("insert into ".db_prefix."stock_status set name='".$product["expedition"]."', language_id='".lang."'");
		}
		$sres=qry("select * from ".db_prefix."stock_status where name='".$product["expedition"]."' and language_id='".lang."' limit 1");
		
		//die ("select * from ".db_prefix."stock_status where name='".$product["expedition"]."' and language_id='".lang."' limit 1");
		qry("update ".db_prefix."product set stock_status_id='".res($sres,0,"stock_status_id")."' where product_id='$id'");
    }else{
		qry("update ".db_prefix."product set stock_status_id='0'  where product_id='$id'");
    }
    
    //x

    
    //langs
    if(trim($product["desc2"])!=""){
        foreach($langs as $lang){
            $test= qry("select * from ".db_prefix."product_description where product_id='$id' and language_id='".$lang."' limit 1 ");
            $upd2=getUpdateCodes($map2,$product);
            
            if(numrows($test)==0){
                qry("
		            insert into ".db_prefix."product_description set ".$upd2.", product_id='$id', language_id='".$lang."'  
	            "); 
            }else{
                qry("
		            update ".db_prefix."product_description set ".$upd2." where product_id='$id' and language_id='".$lang."'  
	            "); 
            }
            
        }
    }
    //x
    

	

	
    $processed[]=$product["code"];
    if($product["option"]!=null){
        $processed_options[$product["code"]][]=$product["option"];	
    }
}
}
}




?>