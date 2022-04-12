<?php
/**
 * User: Marius Mertoiu
 * Date: 10/04/2022 21:52
 * Email: marius.mertoiu@gmail.com
 */

namespace Parsy\Service;

class Logger
{
    public const PARSER_FILE_NAME = 'parser.log';

    public const TYPE_SUCCESS = 'success';
    public const TYPE_ERROR = 'error';
    public const TYPE_NOTICE = 'notice';

    public static function log(
        string $content,
        string $type = self::TYPE_NOTICE,
        string $fileName = self::PARSER_FILE_NAME
    ) {
        $log = date('Y-m-d H:i:s') . ' - ';

        $log .= $type . ': ' . $content . "\n";

        if (!file_exists($_ENV['LOG_PATH'])) {
            mkdir($_ENV['LOG_PATH'], 0755);
        }

        return file_put_contents($_ENV['LOG_PATH'] . $fileName, $log, FILE_APPEND);
    }

    public static function success(
        string $content,
        string $fileName = self::PARSER_FILE_NAME
    ) {
        return self::log($content, self::TYPE_SUCCESS,$fileName);
    }

    public static function error(
        string $content,
        string $fileName = self::PARSER_FILE_NAME
    ) {
        return self::log($content, self::TYPE_ERROR, $fileName);
    }

    public static function notice(
        string $content,
        string $fileName = self::PARSER_FILE_NAME
    ) {
        return self::log($content, self::TYPE_NOTICE, $fileName);
    }
}