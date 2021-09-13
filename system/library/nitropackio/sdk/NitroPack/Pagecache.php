<?php
namespace NitroPack;

class Pagecache {
    protected $url;
    protected $cookies;
    protected $supportedCookies;
    protected $dataDir;
    protected $isAjax;
    protected $Device;
    protected $useCompression;
    protected $useInvalidated;

    public static function getUrlDir($dataDir, $url, $useInvalidated = false) {
        $safeUrl = str_replace(array('/','?',':',';','=','&amp;','&','.','--','%','~','-amp-'),'-', $url);
        if (defined('NITRO_DEBUG_MODE') && NITRO_DEBUG_MODE) {
            $urlDir = $dataDir . "/" . $safeUrl;
        } else {
            $urlDir = $dataDir . "/" . md5($safeUrl);
        }

        if ($useInvalidated) {
            return $urlDir . "_i";
        } else {
            return $urlDir;
        }
    }

    public function __construct($url, $userAgent, $cookies = array(), $supportedCookies = array(), $isAjax = false) {
        $this->url = new Url($url);
        $this->cookies = $cookies;
        $this->supportedCookies = $supportedCookies;
        $this->dataDir = NULL;
        $this->isAjax = $isAjax;
        $this->Device = new Device($userAgent);
        $this->useCompression = false;
        $this->useInvalidated = false;
    }

    public function enableCompression() {
        $this->useCompression = true;
    }

    public function disableCompression() {
        $this->useCompression = false;
    }

    public function useInvalidated($useInvalidated) {
        $this->useInvalidated = $useInvalidated;
    }

    public function setDataDir($dir) {
        if ($dir === null) {
            $this->dataDir = null;
        } else {
            $this->dataDir = $dir . "/" . $this->Device->getType();
        }
    }

    public function hasCache() {
        return file_exists($this->getCachefilePath());
    }

    public function hasExpired($ttl = 86400) {
        return time() - filemtime($this->getCachefilePath()) >= $ttl;
    }

    public function setContent($content) {
        if (!$this->dataDir) return;

        $filePath = $this->getCachefilePath();
        if (Filesystem::createDir(dirname($filePath))) {
            if (!Filesystem::filePutContents($filePath, $content)) {
                return false;
            } else {
                $fileInfo = Filesystem::fileGetAll($filePath);
                $fileInfo->headers["Content-Encoding"] = "gzip";
                $headersFlat = array();
                foreach ($fileInfo->headers as $name => $value) {
                    $headersFlat[] = $name . ":" . $value;
                }
                return Filesystem::filePutContents($filePath . ".gz", gzencode($fileInfo->content, 4), $headersFlat); // save compressed version of the cache file
            }
        }
    }

    public function readfile() {
        $filePath = $this->getCachefilePath();
        if ($this->canUseCompression()) {
            $filePath .= ".gz";
        }
        $file = Filesystem::fileGetAll($filePath);
        foreach ($file->headers as $name => $value) {
            header("$name: $value");
        }

        echo $file->content;
    }

    public function getFileContents() {
        return Filesystem::fileGetContents($this->getCachefilePath());
    }

    private function cookiePrefix() {
        $prefix = '';

        foreach ($this->cookies as $cookieName=>$cookieValue) {
            foreach ($this->supportedCookies as $cookie) {
                if (preg_match('~' . str_replace(array('~', '#asterisk#'), array('\~', '.*'), preg_quote(str_replace('*', '#asterisk#', $cookie))) . '~', $cookieName)) {
                    $prefix .= $cookieName.'='.$cookieValue.';';
                }
            }
        }

        return substr(md5($prefix), 0, 16);
    }

    private function sslPrefix() {
        return $this->url->getScheme() == "https" ? "ssl-" : "";
    }

    private function ajaxPrefix() {
        return $this->isAjax ? "ajax-" : "";
    }

    private function customCachePrefix() {
        $customCachePrefix = NitroPack::getCustomCachePrefix();
        return $customCachePrefix ? $customCachePrefix . "-" : "";
    }

    private function isCompressionAllowed() {
        return isset($_SERVER['HTTP_ACCEPT_ENCODING']) && strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false;
    }

    private function canUseCompression() {
        return $this->useCompression && $this->isCompressionAllowed() && !headers_sent();
    }

    public function nameOfCachefile() {
        return  $this->customCachePrefix() . $this->ajaxPrefix() . $this->sslPrefix() . $this->cookiePrefix() . ".html";
    }

    public function getCachefilePath() {
        return self::getUrlDir($this->dataDir, $this->url->getUrl(), $this->useInvalidated) . "/" . $this->nameOfCachefile();
    }
}
