<?php
if (file_exists('./system/storage/cache/'))
foreach (glob('./system/storage/cache/cache.auto_*.*') as $file)
unlink($file);
?>