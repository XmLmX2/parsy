<?php
/**
 * User: Marius Mertoiu
 * Date: 10/04/2022 18:45
 * Email: marius.mertoiu@gmail.com
 */

// TODO :: Add styling

echo $_GET['type'] ?? 404;

echo '<br>';

if (!empty($_GET['msg'])) {
    echo $_GET['msg'];
}