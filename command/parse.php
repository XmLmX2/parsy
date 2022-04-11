<?php

require_once getcwd() . '/loader.php';

use Parsy\Controller\ParserController;

$fileName = $argv[1] ?? null;

$controller = new ParserController();
$response = $controller->parse($fileName);

// Show some info on console
$status = !empty($response['parse']['status']) ? 'successful' : 'failed';

echo "\n";
echo 'Parse status: ' . $status . ' | ' . ($response['parse']['message'] ?? null) . "\n";
echo "\n";

echo "Database sync stats: \n" .
    "Deleted jobs: " . $response['db_sync']['deleted'] . "\n" .
    "Added jobs: " . $response['db_sync']['added'] . "\n" .
    "Updated jobs: " . $response['db_sync']['updated'] . "\n"
;

echo "\n";

die;