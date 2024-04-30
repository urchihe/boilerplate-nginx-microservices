<?php

namespace App\Enum;

class TestHistoryState
{
    private static bool $enable = true;

    public static function isEnable(): bool
    {
        return self::$enable;
    }

    public static function disable(): void
    {
        self::$enable = false;
    }

    public static function enable(): void
    {
        self::$enable = false;
    }
}
