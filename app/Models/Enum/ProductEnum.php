<?php

namespace App\Models\Enum;

class ProductEnum
{
    // 狀態類別
    const MENU = 'MENU'; // 選單

    public static function getShowName($status)
    {
        switch ($status) {
            case self::MENU:
                return '選單';
            default:
                return '';
        }
    }
}
