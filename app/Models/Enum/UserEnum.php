<?php

namespace App\Models\Enum;

class UserEnum
{
    // 狀態類別
    const INVALID = -1; // 非法
    const NORMAL = 0; //正常
    const FREEZE = 1; //凍結

    public static function getStatusName($status)
    {
        switch ($status) {
            case self::INVALID:
                return 'INVALID';
            case self::NORMAL:
                return 'NORMAL';
            case self::FREEZE:
                return 'FREEZE';
            default:
                return 'NORMAL';
        }
    }
}
