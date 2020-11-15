<?php

$string;
$data;

include_once 'mustache/autoloader.php';
$m = new Mustache_Engine;
echo $m->render('Hello {{planet}}', array('planet' => 'World!'));

?>
