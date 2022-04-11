<?php
/**
 * User: Marius Mertoiu
 * Date: 10/04/2022 18:49
 * Email: marius.mertoiu@gmail.com
 */

namespace Parsy\Service;

class ErrorRedirector
{
    public static function redirect(int $type = 404, ?string $message = null)
    {
        header('Location: ' . $_ENV['WEBROOT'] . '?page=error&type=' . $type . ($message ? '&msg=' . $message : ''));
    }
}