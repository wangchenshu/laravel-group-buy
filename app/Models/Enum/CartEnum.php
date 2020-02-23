<?php

namespace App\Models\Enum;

class CartEnum
{
    // 狀態類別
    const CHECK_CART = 'CHECK_CART'; // 查看購物車
    const CLEAR_CART = 'CLEAR_CART'; // 清除購物車
    const CHECKOUT = 'CHECKOUT'; // 結帳
    const EMPTY_CART = 'EMPTY_CART'; // 購物車無商品
    const ADD_TO_CART = 'ADD_TO_CART'; // 加入購物車
    const ADD_CART_SUCCESS = 'ADD_CART_SUCCESS'; // 加入購物車成功
    const ADD_CART_FAIL = 'ADD_CART_FAIL'; // 加入購物車失敗
    const CLEAR_CART_SUCCESS = 'CLEAR_CART_SUCCESS'; // 清除購物車成功
    const CLEAR_CART_FAIL = 'CLEAR_CART_FAIL'; // 清除購物車失敗
    const CURRENT_CART = 'CURRENT_CART'; // 目前購物車有

    public static function getShowName($status)
    {
        switch ($status) {
            case self::CHECK_CART:
                return '查看購物車';
            case self::CLEAR_CART:
                return '清除購物車';
            case self::CHECKOUT:
                return '結帳';
            case self::EMPTY_CART:
                return '購物車無商品';
            case self::ADD_TO_CART:
                return ' 加入購物車';
            case self::ADD_CART_SUCCESS:
                return ' 加入購物車成功';
            case self::ADD_CART_FAIL:
                return ' 加入購物車失敗';
            case self::CLEAR_CART_SUCCESS:
                return ' 清除購物車成功';
            case self::CLEAR_CART_FAIL:
                return ' 清除購物車失敗';
            case self::CURRENT_CART:
                return ' 目前購物車有';
            default:
                return '';
        }
    }

    public static function getActionName($status)
    {
        switch ($status) {
            case self::ADD_TO_CART:
                return 'addToCart';
            case self::CHECK_CART:
                return 'checkCart';
            case self::CLEAR_CART:
                return 'clearCart';
            case self::CHECKOUT:
                return 'checkout';
            default:
                return '';
        }
    }
}
