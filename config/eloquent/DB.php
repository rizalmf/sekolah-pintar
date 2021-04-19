<?php

namespace app\config\eloquent;

use Illuminate\Database\Capsule\Manager;

class DB extends Manager
{
    private static $myLogger;

    public static function setCustomLogger($customLogger)
    {
        self::$myLogger = $customLogger;
    }

    public static function getCustomLogger()
    {
        return self::$myLogger;
    }
}