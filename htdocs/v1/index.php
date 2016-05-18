<?php
header("Access-Control-Allow-Origin: *");
require __DIR__ . '/../../Jacwright/RestServer/RestServer.php';
require 'SearchController.php';

$server = new \Jacwright\RestServer\RestServer('debug');
$server->addClass('SearchController');
$server->handle();
