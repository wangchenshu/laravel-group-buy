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

class LineMessageController extends Controller
{
    protected $bot;

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
        $messageTitle = '選單';
        $menus = array(
            array(
                'name' => '查看購物車',
                'postBack' => 'action=checkCart',
                'picUrl' => "https://firebasestorage.googleapis.com/v0/b/atomy-bot.appspot.com/o/%E8%89%BE%E5%A4%9A%E7%BE%8E%20%E7%89%A9%E7%90%86%E6%80%A7%E9%98%B2%E6%9B%AC%E8%86%8F.jpg?alt=media&token=e659398b-c5a5-4e0e-ae91-614633d2355b"
            ),
            array(
                'name' => '結帳',
                'postBack' => 'action=checkout',
                'picUrl' => "https://firebasestorage.googleapis.com/v0/b/atomy-bot.appspot.com/o/%E8%89%BE%E5%A4%9A%E7%BE%8E%20%E7%89%A9%E7%90%86%E6%80%A7%E9%98%B2%E6%9B%AC%E8%86%8F.jpg?alt=media&token=e659398b-c5a5-4e0e-ae91-614633d2355b",
            ),
            array(
                'name' => '團購商品',
                'postBack' => 'action=groupBuy',
                'picUrl' => "https://firebasestorage.googleapis.com/v0/b/atomy-bot.appspot.com/o/%E6%B5%B7%E8%8B%94%E7%A6%AE%E7%9B%92.jpg?alt=media&token=4e1e859f-fae6-41de-86f4-94a506c3a2a9",
            ),
            array(
                'name' => '清除購物車',
                'postBack' => 'action=clear',
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
        $messageTitle = '選單';
        $products = Product::all();

        foreach ($products as $product) {
            $productId = $product->id;
            $productName = $product->name;
            $productPrice = '$ ' . $product->price;
            $productUrl = $product->pic_url;

            array_push(
                $data,
                new CarouselColumnTemplateBuilder($productName, $productPrice, $productUrl, [
                    new PostbackTemplateActionBuilder('加入購物車', 'action=add&itemid=' . $productId . '&itemname=' . $productName),
                ])
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

    public function index(Request $request)
    {
        $this->bot = resolve('linebot');
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
                        case '團購商品':
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

                // 團購商品
                if ($action == 'groupBuy') {
                    $this->replyMessage($event, $this->getCarousel());
                }

                // 購物車操作
                if ($action == 'add') {
                    $itemid = $data['itemid'];
                    $itemname = $data['itemname'];
                    $multiple_message_builder
                        ->add(new TextMessageBuilder($itemname . ' 加入購物車成功'))
                        ->add($this->getQuickReply());
                } elseif ($action == 'clear') {
                    $multiple_message_builder
                        ->add(new TextMessageBuilder('清除購物車成功'))
                        ->add($this->getQuickReply());
                } elseif ($action == 'checkCart') {
                    $multiple_message_builder
                        ->add(new TextMessageBuilder('購物車無商品'))
                        ->add($this->getQuickReply());
                } elseif ($action == 'checkout') {
                    $multiple_message_builder
                        ->add(new TextMessageBuilder('結帳完成'))
                        ->add($this->getQuickReply());
                } else {
                    $multiple_message_builder->add($this->getQuickReply());
                }

                $this->replyMessage($event, $multiple_message_builder);
            }
        }
    }
}
