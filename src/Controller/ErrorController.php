<?php
/**
 * User: Marius Mertoiu
 * Date: 11/04/2022 10:31
 * Email: marius.mertoiu@gmail.com
 */

namespace Parsy\Controller;

class ErrorController
{
    public function show()
    {
        $errorType = $_GET['type'] ?? 404;
        $message = $_GET['msg'] ?? 'An error occurred';

        $pageTitle = 'Error ' . $errorType;

        include_once $_ENV['VIEW_PATH'] . 'error/show.php';
    }
}