<?php

require_once 'bootstrap.php';

use Parsy\Controller\JobController;
use Parsy\Controller\ErrorController;

$page = $_GET['page'] ?? 'list';

// Load error page
if ($page === 'error') {
    $errorController = new ErrorController();
    $errorController->show();

    die;
}

// Load listing page
$jobController = new JobController();
$jobController->list();