<?php

namespace App\Models\Enum;

class OrderEnum
{
    // 狀態類別
    const GROUP_BUY_PRODUCT = 'GROUP_BUY_PRODUCT'; // 團購商品
    const CHECKOUT_SUCCESS = 'CHECKOUT_SUCCESS'; // 結帳完成
    const CHECKOUT_FAIL = 'CHECKOUT_FAIL'; // 結帳失敗
    const PRICE = 'PRICE'; // 價格
    const QTY = 'QTY'; // 數量
    const TOTAL_PRICE = 'TOTAL_PRICE'; // 總計
    const MONEY_TRANSFER = 'MONEY_TRANSFER'; // 匯款
    const TRANSFER_BANK_NUM = 'TRANSFER_BANK_NUM'; // 銀行代號
    const TRANSFER_ACCOUNT = 'TRANSFER_ACCOUNT'; // 帳戶
    const TRANSFER_AMOUNT = 'TRANSFER_AMOUNT'; // 匯款金額

    public static function getShowName($status)
    {
        switch ($status) {
            case self::GROUP_BUY_PRODUCT:
                return '團購商品';
            case self::CHECKOUT_SUCCESS:
                return '結帳完成';
            case self::CHECKOUT_FAIL:
                return '結帳失敗';
            case self::PRICE:
                return '價格';
            case self::QTY:
                return '數量';
            case self::TOTAL_PRICE:
                return '合計';
            case self::MONEY_TRANSFER:
                return '請於2日內，匯款至以下指定帳戶，我們收到後會儘快為您出貨，謝謝您的配合。';
            case self::TRANSFER_ACCOUNT:
                return '帳戶';
            case self::TRANSFER_BANK_NUM:
                return '銀行代號';
            case self::TRANSFER_AMOUNT:
                return '匯款金額';
            default:
                return '';
        }
    }

    public static function getActionName($status)
    {
        switch ($status) {
            case self::GROUP_BUY_PRODUCT:
                return 'groupBuy';
            default:
                return '';
        }
    }
}
