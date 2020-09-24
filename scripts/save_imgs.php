<?php 

use SQLi\SQLi as sqli;

$dir = "../app/public/img/profiles/";

$values = array_diff(scandir($dir), array('..', '.'));

foreach ($values as $img) {
    sqli::exec("INSERT INTO imagens_perfil (slug, used) VALUES ('$img', 0)");
}