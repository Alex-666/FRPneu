<?php
namespace NitroPack;

class Filesystem {
    public static function getOsPath($parts) {
        return implode(DIRECTORY_SEPARATOR, $parts);
    }

    public static function deleteFile($path) {
        if (self::fileExists($path)) {
            return @unlink($path);
        }

        return true;
    }

    public static function createDir($dir) {
        if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
            return false;
        }

        return true;
    }

    public static function deleteDir($dir) {
        if (!is_dir($dir)) return true;
        return self::trunkDir($dir) && rmdir($dir);
    }

    public static function trunkDir($dir) {
        if (!is_dir($dir)) return true;
        $dh = opendir($dir);
        if ($dh === false) return false;

        while (false !== ($entry = readdir($dh))) {
            if ($entry == "." || $entry == "..") continue;
            $path = Filesystem::getOsPath(array($dir, $entry));
            if (is_dir($path)) {
                if (!self::deleteDir($path)) {
                    closedir($dh);
                    return false;
                }
            } else {
                if (!unlink($path)) {
                    closedir($dh);
                    return false;
                }
            }
        }
        closedir($dh);

        return true;
    }

    public static function isDirEmpty($dir) {
        if (!is_dir($dir)) return false;
        $dh = opendir($dir);
        if ($dh === false) return false;

        $isEmpty = true;
        while (false !== ($entry = readdir($dh))) {
            if ($entry == "." || $entry == "..") continue;
            $isEmpty = false; // first entry which is not "." or ".." means the dir is not empty
            break;
        }
        closedir($dh);

        return $isEmpty;
    }

    public static function createCacheDir($dir) {
        if (!self::createDir($dir . "/mobile")
            || !self::createDir($dir . "/tablet")
            || !self::createDir($dir . "/desktop")
        ) {
            return false;
        }

        return true;
    }

    public static function dirForeach($dir, $callback) {
        if (!is_dir($dir)) return false;
        $dh = opendir($dir);
        if ($dh === false) return false;

        while (false !== ($entry = readdir($dh))) {
            if ($entry == "." || $entry == "..") continue;
            call_user_func($callback, Filesystem::getOsPath(array($dir, $entry)));
        }
        closedir($dh);
        return true;
    }

    public static function fileMTime($filePath) {
        return self::fileExists($filePath) ? @filemtime($filePath) : 0;
    }

    public static function fileGetHeaders($filePath) {
        return self::fileGetAll($filePath)->headers;
    }

    public static function fileGetContents($filePath) {
        return self::fileGetAll($filePath)->content;
    }

    public static function fileSize($filePath) {
        return self::fileGetAll($filePath)->size;
    }

    public static function fileExists($filePath) {
        return file_exists($filePath);
    }

    public static function fileGetAll($filePath) {
        if (!self::fileExists($filePath)) throw new \Exception("File not found: $filePath");

        $res = new \stdClass();
        $res->headers = [];
        $res->content = "";
        $res->size = 0;

        list($res->headers, $res->content) = self::explodeByHeaders(file_get_contents($filePath));
        $res->size = strlen($res->content);

        return $res;
    }

    public static function filePutContents($file, $content, $headers = NULL) {
        if ($headers === NULL) {
            return @file_put_contents($file, $content);
        } else if (is_array($headers)) {
            $headerStr = implode("\r\n", $headers) . "\r\n\r\n";
            return @file_put_contents($file, $headerStr . $content);
        } else if (is_string($headers)) {
            return @file_put_contents($file, $headers . $content);
        } else {
            return @file_put_contents($file, $content);
        }
    }

    public static function rename($oldName, $newName) {
        if (self::fileExists($oldName)) {
            return @rename($oldName, $newName);
        }

        return false;
    }

    private static function explodeByHeaders($content) {
        $headers = [];
        $pos = strpos($content, "\r\n\r\n");
        if ($pos !== false) {
            $headerStr = substr($content, 0, $pos);
            $content = substr($content, $pos+4);
            $lines = explode("\r\n", $headerStr);
            foreach ($lines as $line) {
                $parts = explode(":", $line);
                $name = strtolower(array_shift($parts));
                $value = trim(implode(":", $parts));
                $headers[$name] = $value;
            }
        }

        return array($headers, $content);
    }
}
