<?php
/**
 * User: Marius Mertoiu
 * Date: 10/04/2022 18:29
 * Email: marius.mertoiu@gmail.com
 */

require_once getcwd() . '/loader.php';

use Parsy\Service\RunningModeService;
use Parsy\Controller\InstallController;

// Allow only CLI run
RunningModeService::redirectIfNotCli();

$controller = new InstallController();
$response = $controller->install();

// Show some info on console
$status = !empty($response["status"]) ? "successful" : "failed";

echo "\n";
echo "General status: " . $status . "\n";
echo "\n";

foreach ($response['info'] as $key => $info) {
    $status = !empty($info["status"]) ? "successful" : "failed";

    echo $key . " status: " . $status . " | " . $info['message'] . "\n";
}

echo "\n";

die;