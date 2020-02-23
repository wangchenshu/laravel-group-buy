<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use LINE\LINEBot\Constant\HTTPHeader;
use LINE\LINEBot\Event\MessageEvent;
use LINE\LINEBot\Event\MessageEvent\TextMessage;
use LINE\LINEBot\Event\MessageEvent\StickerMessage;
use LINE\LINEBot\Event\PostbackEvent;

use LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\QuickReplyBuilder\ButtonBuilder\QuickReplyButtonBuilder;
use LINE\LINEBot\QuickReplyBuilder\QuickReplyMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\KitchenSink\EventHandler\MessageHandler\Util\UrlBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;

use App\Models\Product;
use App\Models\Cart;
use App\Models\Enum\CartEnum;
use App\Models\Enum\OrderEnum;
use App\Models\Enum\ProductEnum;

class LineMessageController extends Controller
{
    protected $bot;

    public function __construct()
    {
        $this->bot = resolve('linebot');
    }

    private function getUserDisplayName($userId)
    {
        $res = $this->bot->getProfile($userId);
        $displayName = "";

        if ($res->isSucceeded()) {
            $profile = $res->getJSONDecodedBody();
            $displayName = $profile['displayName'];
        }

        return $displayName;
    }

    private function getQuickReply()
    {
        $data = [];
        $messageTitle = ProductEnum::getShowName('MENU');
        $menus = array(
            array(
                'name' => CartEnum::getShowName('CHECK_CART'),
                'postBack' => 'action=' . CartEnum::getActionName('CHECK_CART'),
                'picUrl' => "https://firebasestorage.googleapis.com/v0/b/atomy-bot.appspot.com/o/%E8%89%BE%E5%A4%9A%E7%BE%8E%20%E7%89%A9%E7%90%86%E6%80%A7%E9%98%B2%E6%9B%AC%E8%86%8F.jpg?alt=media&token=e659398b-c5a5-4e0e-ae91-614633d2355b"
            ),
            array(
                'name' => CartEnum::getShowName('CHECKOUT'),
                'postBack' => 'action=' . CartEnum::getActionName('CHECKOUT'),
                'picUrl' => "https://firebasestorage.googleapis.com/v0/b/atomy-bot.appspot.com/o/%E8%89%BE%E5%A4%9A%E7%BE%8E%20%E7%89%A9%E7%90%86%E6%80%A7%E9%98%B2%E6%9B%AC%E8%86%8F.jpg?alt=media&token=e659398b-c5a5-4e0e-ae91-614633d2355b",
            ),
            array(
                'name' => OrderEnum::getShowName('GROUP_BUY_PRODUCT'),
                'postBack' => 'action=' . OrderEnum::getActionName('GROUP_BUY_PRODUCT'),
                'picUrl' => "https://firebasestorage.googleapis.com/v0/b/atomy-bot.appspot.com/o/%E6%B5%B7%E8%8B%94%E7%A6%AE%E7%9B%92.jpg?alt=media&token=4e1e859f-fae6-41de-86f4-94a506c3a2a9",
            ),
            array(
                'name' => CartEnum::getShowName('CLEAR_CART'),
                'postBack' => 'action=' . CartEnum::getActionName('CLEAR_CART'),
                'picUrl' => "https://firebasestorage.googleapis.com/v0/b/atomy-bot.appspot.com/o/%E8%89%BE%E5%A4%9A%E7%BE%8E%20%E7%89%A9%E7%90%86%E6%80%A7%E9%98%B2%E6%9B%AC%E8%86%8F.jpg?alt=media&token=e659398b-c5a5-4e0e-ae91-614633d2355b",
            ),
        );

        foreach ($menus as $menu) {
            array_push(
                $data,
                new QuickReplyButtonBuilder(
                    new PostbackTemplateActionBuilder(
                        $menu['name'],
                        $menu['postBack'],
                        $menu['name']
                    ),
                    $menu['picUrl']
                )
            );
        }

        $quickReply = new QuickReplyMessageBuilder($data);
        $textMessageTemplate = new TextMessageBuilder($messageTitle, $quickReply);

        return $textMessageTemplate;
    }

    private function getCarousel()
    {
        $data = [];
        $messageTitle = ProductEnum::getShowName('MENU');
        $products = Product::all();

        foreach ($products as $product) {
            $productId = $product->id;
            $productName = $product->name;
            $productPrice = $product->price;
            $productUrl = $product->pic_url;

            array_push(
                $data,
                new CarouselColumnTemplateBuilder(
                    $productName,
                    '$ ' . $productPrice,
                    $productUrl,
                    [
                        new PostbackTemplateActionBuilder(
                            CartEnum::getShowName('ADD_TO_CART'),
                            'action='
                                . CartEnum::getActionName('ADD_TO_CART')
                                . '&itemId=' . $productId
                                . '&itemName=' . $productName
                                . '&itemPrice=' . $productPrice
                        ),
                    ]
                )
            );
        }

        $carouselTemplateBuilder = new CarouselTemplateBuilder($data);
        $messageTemplate = new TemplateMessageBuilder($messageTitle, $carouselTemplateBuilder);

        return $messageTemplate;
    }

    private function replyMessage($event, $messageTemplate)
    {
        $this->bot->replyMessage(
            $event->getReplyToken(),
            $messageTemplate
        );
    }

    private function addToCart($productName, $lineUserId, $username, $qty, $price)
    {
        $cart = Cart::create([
            'product_name' => $productName,
            'line_user_id' => $lineUserId,
            'username' => $username,
            'qty' => $qty,
            'price' => $price,
        ]);
        return $cart;
    }

    private function getCartByLineUserId($lineUserId)
    {
        $carts = Cart::where('line_user_id', $lineUserId)->get();
        return $carts;
    }

    private function clearCartByLineUserId($lineUserId)
    {
        Cart::where('line_user_id', $lineUserId)->delete();
        return true;
    }

    private function showCurrentCartByLineUserId($userId)
    {
        $carts = $this->getCartByLineUserId($userId);
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

    public function index(Request $request)
    {
        $signature = $request->header(HTTPHeader::LINE_SIGNATURE);
        $body = $request->getContent();
        $events = $this->bot->parseEventRequest($body, $signature);

        foreach ($events as $event) {
            if ($event instanceof MessageEvent) {
                if ($event instanceof TextMessage) {
                    $userId = $event->getUserId();
                    $text = strtolower($event->getText());
                    $messageTemplate = null;
                    $multiple_message_builder = new MultiMessageBuilder();

                    switch ($text) {
                        case OrderEnum::getShowName('GROUP_BUY_PRODUCT'):
                            $messageTemplate = $this->getCarousel();
                            break;
                        default:
                            $messageTemplate = $multiple_message_builder->add($this->getQuickReply());
                            break;
                    }

                    $this->replyMessage($event, $messageTemplate);
                } elseif ($event instanceof StickerMessage) {
                    $this->replyMessage(
                        $event,
                        new StickerMessageBuilder(
                            $event->getPackageId(),
                            $event->getStickerId()
                        )
                    );
                }
            } elseif ($event instanceof PostbackEvent) {
                parse_str($event->getPostbackData(), $data);
                $action = $data['action'];
                $multiple_message_builder = new MultiMessageBuilder();
                $userId = $event->getUserId();
                $displayName = $this->getUserDisplayName($userId);

                if ($action == OrderEnum::getActionName('GROUP_BUY_PRODUCT')) {
                    $multiple_message_builder
                        ->add($this->getCarousel())
                        ->add($this->getQuickReply());
                } elseif ($action == CartEnum::getActionName('ADD_TO_CART')) {
                    $itemName = $data['itemName'];
                    $itemPrice = $data['itemPrice'];
                    $cart = $this->addToCart($itemName, $userId, $displayName, 1, $itemPrice);
                    $resStr = CartEnum::getShowName('ADD_CART_SUCCESS');

                    if (!$cart->product_name == $itemName) {
                        $resStr = CartEnum::getShowName('ADD_CART_FAIL');
                    }
                    $multiple_message_builder
                        ->add(new TextMessageBuilder($itemName . $resStr))
                        ->add($this->getQuickReply());
                } elseif ($action == CartEnum::getActionName('CLEAR_CART')) {
                    $resStr = CartEnum::getShowName('CLEAR_CART_FAIL');

                    if ($this->clearCartByLineUserId($userId)) {
                        $resStr = CartEnum::getShowName('CLEAR_CART_SUCCESS');
                    }
                    $multiple_message_builder
                        ->add(new TextMessageBuilder($resStr))
                        ->add($this->getQuickReply());
                } elseif ($action == CartEnum::getActionName('CHECK_CART')) {
                    $multiple_message_builder
                        ->add(new TextMessageBuilder($this->showCurrentCartByLineUserId($userId)))
                        ->add($this->getQuickReply());
                } elseif ($action == CartEnum::getActionName('CHECKOUT')) {
                    $carts = $this->getCartByLineUserId($userId);
                    $resStr = CartEnum::getShowName('EMPTY_CART');
                    $testBankNum = '007';
                    $testAccount = '001234567899999';

                    if (count($carts) > 0) {
                        $resStr = $this->showCurrentCartByLineUserId($userId);
                        $totalPrice = $carts->sum->price;

                        if ($this->clearCartByLineUserId($userId)) {
                            $resStr .= OrderEnum::getShowName('MONEY_TRANSFER') . PHP_EOL;
                            $resStr .= PHP_EOL . OrderEnum::getShowName('TRANSFER_BANK_NUM') . ': ' . $testBankNum . PHP_EOL;
                            $resStr .= OrderEnum::getShowName('TRANSFER_ACCOUNT') . ': ' . $testAccount . PHP_EOL;
                            $resStr .= OrderEnum::getShowName('TRANSFER_AMOUNT') . ': $ ' . $totalPrice . PHP_EOL;
                            $resStr .= PHP_EOL . OrderEnum::getShowName('CHECKOUT_SUCCESS');
                        } else {
                            $resStr = OrderEnum::getShowName('CHECKOUT_FAIL');
                        }
                    }

                    $multiple_message_builder
                        ->add(new TextMessageBuilder($resStr))
                        ->add($this->getQuickReply());
                } else {
                    $multiple_message_builder->add($this->getQuickReply());
                }

                $this->replyMessage($event, $multiple_message_builder);
            }
        }
    }
}
