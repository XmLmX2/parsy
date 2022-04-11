<?php

require_once getcwd() . '/loader.php';
//require_once '../loader.php';

use Parsy\Controller\ParserController;

$fileName = $argv[1] ?? null;

$controller = new ParserController();
$response = $controller->parse();

echo '<pre>';
echo __FILE__ . ':' . __LINE__ . '<br>';
var_dump($response);
echo '</pre>';
exit;

// TODO :: Style the response