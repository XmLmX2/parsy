<?php

require_once 'config/loader.php';

use Parsy\Service\DataParser;
use Parsy\Core\Database;

$db = new Database();

echo '<pre>';
echo __FILE__ . ':' . __LINE__ . '<br>';
var_dump($db);
echo '</pre>';
exit;


echo '<pre>';
echo __FILE__ . ':' . __LINE__ . '<br>';
var_dump(UPLOADS_PATH);
echo '</pre>';
exit;

$parser = new DataParser();

echo '<pre>';
echo __FILE__ . ':' . __LINE__ . '<br>';
var_dump($parser->test());
echo '</pre>';
exit;