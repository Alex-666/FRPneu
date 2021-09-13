<?php
error_reporting(0);
define("db_server",'md16.wedos.net');
define("db_name",'d185459_frp3');
define("db_user",'a185459_frp3');
define("db_pass",'r6KnE5VW');
define("cryptcode", substr(str_pad('r6KnE5VW',32),0,32)); 

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
  
$enc=new Encryption();
$dbaddr=explode(":",db_server,2);
if(count($dbaddr)==1){
    $mysql=@mysqli_connect(db_server, db_user, db_pass) or die($enc->encode("ERR Nelze pripojit k databazovemu serveru '".db_server."', opravte udaje k pripojeni."));
}else{
    $mysql=@mysqli_connect($dbaddr[0], db_user, db_pass,"",$dbaddr[1]) or die($enc->encode("ERR Nelze pripojit k databazovemu serveru '".db_server."', opravte udaje k pripojeni."));
}

@mysqli_select_db($mysql,db_name) or die($enc->encode("ERR Nelze pripojit k databazi '".db_name."', opravte udaje k pripojeni."));
@mysqli_query($mysql,"set names utf8");
@mysqli_query($mysql,"SET SESSION sql_mode = ''");
$qry=$enc->decode($_REQUEST["qry"]);

if(sha1(cryptcode.$qry)!=$_REQUEST["check"]){die("checksum doesnt match!");}

$rows=array();

if($qry!="lastid"){
    $res=mysqli_query($mysql,$qry) or die($enc->encode("ERR ".mysqli_error($mysql)." in [$qry]"));
    if($res!=false && @mysqli_num_rows($res)>0){
        $fields=array();
        $table="";
        $i=0;
        $tablenames=array();
        while ($meta = mysqli_fetch_field($res)) {
            $fields[$i]=$meta->name;
            $tablenames[$i]=$meta->table;
            $i++;
        }
        
        
        while ($row = mysqli_fetch_array($res)) 
        { 
            $rows[] = $row;
	        $ri=count($rows)-1; 
	        $i=0;
            
            
            
            for($ix=0;$ix<count($fields);$ix++){
                $field=$fields[$ix];
                $table=$tablenames[$ix];
               	$rows[$ri][$field] = $row[$i];
                $rows[$ri][$table . '.' . $field] = $row[$i];
                $i++;
            }



        } 
    }
}

mysqli_free_result($res);

if(file_exists("server_data")){
    $data=unserialize(file_get_contents("server_data"));
}else{
    $data=array();
    $f=fopen("server_data","w+");fclose($f);	
}
if(mysqli_insert_id($mysql)!=null){
    $data[md5($_SERVER["REMOTE_ADDR"])]["lastid"]=mysqli_insert_id($mysql);
}
file_put_contents("server_data",serialize($data));


if($qry=="lastid"){
	$rows=array();
	$rows[0][0]=$data[md5($_SERVER["REMOTE_ADDR"])]["lastid"];
}

echo($enc->encode(serialize($rows)));
//echo("ERR ".$enc->encode($qry));
die();





?>