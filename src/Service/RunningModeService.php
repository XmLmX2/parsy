<?php
/**
 * User: Marius Mertoiu
 * Date: 10/04/2022 20:51
 * Email: marius.mertoiu@gmail.com
 */

namespace Parsy\Service;

class RunningModeService
{
    public static function isCli(): bool
    {
        return php_sapi_name() === "cli";
    }

    public static function redirectIfNotCli()
    {
        if (!self::isCli()) {
            ErrorRedirector::redirect(403,'You are not allowed to be here!');
        }
    }
}