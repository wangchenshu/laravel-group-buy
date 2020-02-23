<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\Api\ChatfuelAddCartRequest;
use App\Http\Requests\Api\ChatfuelCheckCartRequest;
use App\Models\ChatfuelCart;
use App\Models\Enum\CartEnum;
use App\Models\Enum\OrderEnum;

class ChatfuelController extends Controller
{
    private function getCartByMessengerUserId($userId)
    {
        $carts = ChatfuelCart::where('messenger_user_id', $userId)->get();
        return $carts;
    }

    private function clearCartByMessengerUserId($userId)
    {
        ChatfuelCart::where('messenger_user_id', $userId)->delete();
        return true;
    }

    private function showCurrentCartByMessengerUserId($userId)
    {
        $carts = $this->getCartByMessengerUserId($userId);
        $resStr = CartEnum::getShowName('EMPTY_CART');
        if (count($carts) > 0) {
            $resStr = CartEnum::getShowName('CURRENT_CART') . ': ' . PHP_EOL . PHP_EOL;
            $totalPrice = $carts->sum->price;

            foreach ($carts as $cart) {
                $resStr .= $cart->product_name . ', ';
                $resStr .= OrderEnum::getShowName('PRICE') . ': $ ' . $cart->price . ', ';
                $resStr .= OrderEnum::getShowName('QTY') . ': ' . $cart->qty . PHP_EOL;
            }
            $resStr .= PHP_EOL . OrderEnum::getShowName('TOTAL_PRICE') . ': $ ' . $totalPrice . PHP_EOL;
        }

        return $resStr;
    }

    public function addToCart(ChatfuelAddCartRequest $request)
    {
        $cart = ChatfuelCart::create($request->all());
        $response = [
            'messages' => [['text' => $cart->product_name . CartEnum::getShowName('ADD_CART_SUCCESS')]]
        ];

        return response()->json($response);
    }

    public function checkCart(ChatfuelCheckCartRequest $request)
    {
        $resStr = $this->showCurrentCartByMessengerUserId($request['messenger_user_id']);
        $response = [
            'messages' => [['text' => $resStr]]
        ];

        return response()->json($response);
    }

    public function clearCart(ChatfuelCheckCartRequest $request)
    {
        $this->clearCartByMessengerUserId($request['messenger_user_id']);
        $response = [
            'messages' => [['text' => CartEnum::getShowName('CLEAR_CART_SUCCESS')]]
        ];

        return response()->json($response);
    }

    public function checkout(ChatfuelCheckCartRequest $request)
    {
        $userId = $request['messenger_user_id'];
        $carts = $this->getCartByMessengerUserId($userId);
        $resStr = CartEnum::getShowName('EMPTY_CART');
        $testBankNum = '007';
        $testAccount = '001234567899999';

        if (count($carts) > 0) {
            $resStr = $this->showCurrentCartByMessengerUserId($userId);
            $totalPrice = $carts->sum->price;

            if ($this->clearCartByMessengerUserId($userId)) {
                $resStr .= OrderEnum::getShowName('MONEY_TRANSFER') . PHP_EOL;
                $resStr .= PHP_EOL . OrderEnum::getShowName('TRANSFER_BANK_NUM') . ': ' . $testBankNum . PHP_EOL;
                $resStr .= OrderEnum::getShowName('TRANSFER_ACCOUNT') . ': ' . $testAccount . PHP_EOL;
                $resStr .= OrderEnum::getShowName('TRANSFER_AMOUNT') . ': $ ' . $totalPrice . PHP_EOL;
                // $resStr .= PHP_EOL . OrderEnum::getShowName('CHECKOUT_SUCCESS');
            } else {
                $resStr = OrderEnum::getShowName('CHECKOUT_FAIL');
            }
        }

        $response = [
            'messages' => [['text' => $resStr]]
        ];

        return response()->json($response);
    }
}
