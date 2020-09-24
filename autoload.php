<?php

spl_autoload_register(function($name) {
	
    $file = "../src/".$name.".php";

    if (file_exists($file) && is_readable($file)) {
        include $file;
        return;
    }
    
});

require_once "../helpers/helper.php";
require_once "../helpers/routes.php";

$lib   = "../lib/";
$scann = array_diff(scandir($lib), array('..', '.'));

foreach ($scann as $x) {
    $file = $lib."/".$x."/autoload.php";
    if (file_exists($file) && is_readable($file)) include $file;
}