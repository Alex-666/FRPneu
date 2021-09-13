<?php
error_reporting(0);
define("db_server", '18.198.147.70');
define("db_name", 'frpneucz');
define("db_user", 'frpneu_synch');
define("db_pass", '#72e4gsJ');
define("cryptcode", substr(str_pad('#72e4gsJ', 32), 0, 32));

class MCryptEncryption
{
    public $skey = cryptcode;

    public function safe_b64encode($string)
    {
        $data = base64_encode($string);
        $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
        return $data;
    }

    public function safe_b64decode($string)
    {
        $data = str_replace(array('-', '_'), array('+', '/'), $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    public function encode($value)
    {
        if (!$value) {return false;}
        $text = $value;
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->skey, $text, MCRYPT_MODE_ECB, $iv);
        return trim($this->safe_b64encode($crypttext));
    }

    public function decode($value)
    {
        if (!$value) {return false;}
        $crypttext = $this->safe_b64decode($value);
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->skey, $crypttext, MCRYPT_MODE_ECB, $iv);
        return trim($decrypttext);
    }
}

class OpenSSLEncryption
{
    const METHOD = 'aes-256-cbc';
    public $skey = cryptcode;

    public function encode($message)
    {
        if (mb_strlen($this->skey, '8bit') !== 32) {
            throw new Exception("Needs a 256-bit key!");
        }
        $ivsize = openssl_cipher_iv_length(self::METHOD);
        $iv = mb_substr(base64_encode(openssl_random_pseudo_bytes($ivsize)), 0, $ivsize, '8bit');

        $ciphertext = openssl_encrypt(
            $message,
            self::METHOD,
            $this->skey,
            null,
            $iv
        );

        return base64_encode($iv . $ciphertext);
    }

    public function decode($message)
    {

        $message = base64_decode($message);
        if (mb_strlen($this->skey, '8bit') !== 32) {
            throw new Exception("Needs a 256-bit key!");
        }
        $ivsize = openssl_cipher_iv_length(self::METHOD);
        $iv = mb_substr($message, 0, $ivsize, '8bit');
        $ciphertext = mb_substr($message, $ivsize, null, '8bit');

        return openssl_decrypt(
            $ciphertext,
            self::METHOD,
            $this->skey,
            null,
            $iv
        );
    }
}

if ($_POST["method"] == 2) {
    $enc = new OpenSSLEncryption();
} elseif ($_POST["method"] == 1) {
    $enc = new MCryptEncryption();
} else {
    die("ERR Neni k dispozici pozadovana knihovna pro sifrovani dat.");
}

$dbaddr = explode(":", db_server, 2);
if (count($dbaddr) == 1) {
    $mysql = @mysqli_connect(db_server, db_user, db_pass) or die($enc->encode("ERR Nelze pripojit k databazovemu serveru '" . db_server . "', opravte udaje k pripojeni."));
} else {
    $mysql = @mysqli_connect($dbaddr[0], db_user, db_pass, "", $dbaddr[1]) or die($enc->encode("ERR Nelze pripojit k databazovemu serveru '" . db_server . "', opravte udaje k pripojeni."));
}

@mysqli_select_db($mysql, db_name) or die($enc->encode("ERR Nelze pripojit k databazi '" . db_name . "', opravte udaje k pripojeni."));
@mysqli_query($mysql, "set names utf8");
@mysqli_query($mysql, "SET SESSION sql_mode = ''");
$qry = $enc->decode($_REQUEST["qry"]);

//echo "SERVER:".$qry." ... ".$_REQUEST["check"]." == ".sha1(cryptcode.$qry)."\n";

if (sha1(cryptcode . $qry) != $_REQUEST["check"]) {die("ERR Pristup zakazan");}

$rows = array();

if ($qry != "lastid") {
    $res = mysqli_query($mysql, $qry) or die($enc->encode("ERR " . mysqli_error($mysql) . " in [$qry]"));
    if ($res != false && @mysqli_num_rows($res) > 0) {
        $fields = array();
        $table = "";
        $i = 0;
        $tablenames = array();
        while ($meta = mysqli_fetch_field($res)) {
            $fields[$i] = $meta->name;
            $tablenames[$i] = $meta->table;
            $i++;
        }

        while ($row = mysqli_fetch_array($res)) {
            $rows[] = $row;
            $ri = count($rows) - 1;
            $i = 0;

            for ($ix = 0; $ix < count($fields); $ix++) {
                $field = $fields[$ix];
                $table = $tablenames[$ix];
                $rows[$ri][$field] = $row[$i];
                $rows[$ri][$table . '.' . $field] = $row[$i];
                $i++;
            }

        }
    }
}

mysqli_free_result($res);

if (file_exists("server_data")) {
    $data = unserialize(file_get_contents("server_data"));
} else {
    $data = array();
    $f = fopen("server_data", "w+");
    fclose($f);
}
if (mysqli_insert_id($mysql) != null) {
    $data[md5($_SERVER["REMOTE_ADDR"])]["lastid"] = mysqli_insert_id($mysql);
}
file_put_contents("server_data", serialize($data));

if ($qry == "lastid") {
    $rows = array();
    $rows[0][0] = $data[md5($_SERVER["REMOTE_ADDR"])]["lastid"];
}

echo ($enc->encode(serialize($rows)));

die();
