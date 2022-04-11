<?php
/**
 * User: Marius Mertoiu
 * Date: 11/04/2022 10:07
 * Email: marius.mertoiu@gmail.com
 */

namespace Parsy\Controller;

class JobController
{
    public function list()
    {
        $pageTitle = 'Jobs list';

        include_once $_ENV['VIEW_PATH'] . 'job/list.php';
    }
}