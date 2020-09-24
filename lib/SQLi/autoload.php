<?php 

spl_autoload_register(function($name) {
	
	$prefix = 'SQLi\\';

	if(strpos($name, $prefix) !== 0){
		// não é uma classe da biblioteca SQLi
		return;
	}

	$nameFile = str_replace($prefix, "", $name);

	$file = __DIR__.DIRECTORY_SEPARATOR."src".DIRECTORY_SEPARATOR.$nameFile.".php";

    if (file_exists($file) && is_readable($file)) {
        include $file;
        return;
    }

    throw new Exception("Esta classe {$nameFile} não existe na biblioteca SQLi", 1);

});