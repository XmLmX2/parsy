<?php
/**
 * User: Marius Mertoiu
 * Date: 10/04/2022 18:52
 * Email: marius.mertoiu@gmail.com
 */

require_once str_replace('/command', '', realpath(dirname(__FILE__))) . '/bootstrap.php';

use Parsy\Service\RunningModeService;
use Parsy\Controller\ParserController;

// Allow only CLI run
//RunningModeService::redirectIfNotCli();

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