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

    const TEST_BANK_NUM     = '007'; // 測試銀行帳號代碼
    const TEST_BANK_ACCOUNT = '001234567899999'; // 測試銀行帳號
    const IMG_URL_OPTION_GROUP_BUY_PRODUCT  = 'https://firebasestorage.googleapis.com/v0/b/atomy-bot.appspot.com/o/%E6%B5%B7%E8%8B%94%E7%A6%AE%E7%9B%92.jpg?alt=media&token=4e1e859f-fae6-41de-86f4-94a506c3a2a9';
    const IMG_URL_OPTION_CHECK_CART  = 'https://firebasestorage.googleapis.com/v0/b/atomy-bot.appspot.com/o/%E8%89%BE%E5%A4%9A%E7%BE%8E%20%E7%89%A9%E7%90%86%E6%80%A7%E9%98%B2%E6%9B%AC%E8%86%8F.jpg?alt=media&token=e659398b-c5a5-4e0e-ae91-614633d2355b';
    const IMG_URL_OPTION_CHECKOUT  = 'https://firebasestorage.googleapis.com/v0/b/atomy-bot.appspot.com/o/%E8%89%BE%E5%A4%9A%E7%BE%8E%20%E7%89%A9%E7%90%86%E6%80%A7%E9%98%B2%E6%9B%AC%E8%86%8F.jpg?alt=media&token=e659398b-c5a5-4e0e-ae91-614633d2355b';
    const IMG_URL_OPTION_CLEAR_CART  = 'https://firebasestorage.googleapis.com/v0/b/atomy-bot.appspot.com/o/%E8%89%BE%E5%A4%9A%E7%BE%8E%20%E7%89%A9%E7%90%86%E6%80%A7%E9%98%B2%E6%9B%AC%E8%86%8F.jpg?alt=media&token=e659398b-c5a5-4e0e-ae91-614633d2355b';

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
                return '匯款帳戶';
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
