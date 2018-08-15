<?php


require_once('vendor/autoload.php');

$uri = new \SEOCLI\URI('https://www.hdnet.de/');

$request = new \SEOCLI\Request($uri);
var_dump($request->getContent());
var_dump($request->getHeader());
var_dump($request->getMeta());

$climate = new \League\CLImate\CLImate();

$climate->out('This prints to the terminal.');
