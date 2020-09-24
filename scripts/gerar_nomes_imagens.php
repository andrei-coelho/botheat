<?php 

$dir = "../app/public/img/profiles/";

$values = array_diff(scandir($dir), array('..', '.'));

foreach ($values as $img) {
    rename($dir.$img, $dir.sha1($img).".jpg");
}