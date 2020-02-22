<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use LINE\LINEBot\Constant\HTTPHeader;
use LINE\LINEBot\Event\MessageEvent;
use LINE\LINEBot\Event\MessageEvent\TextMessage;
use LINE\LINEBot\Event\MessageEvent\StickerMessage;

use LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

class LineMessageController extends Controller
{
    protected $bot;

    public function index(Request $request)
    {
        $this->bot = resolve('linebot');

        $signature = $request->header(HTTPHeader::LINE_SIGNATURE);
        $body = $request->getContent();
        $events = $this->bot->parseEventRequest($body, $signature);

        foreach ($events as $event) {
            if ($event instanceof MessageEvent) {
                if ($event instanceof TextMessage) {
                    $text = $event->getText();
                    $resp_text = $text;

                    $multiple_message_builder = new MultiMessageBuilder();
                    $multiple_message_builder
                        ->add(new TextMessageBuilder($resp_text));

                    $this->bot->replyMessage(
                        $event->getReplyToken(),
                        $multiple_message_builder
                    );

                    // $userId = $event->getUserId();
                    // $res = $this->bot->getProfile($userId);
                    // if ($res->isSucceeded()) {
                    //     $profile = $res->getJSONDecodedBody();
                    //     $displayName = $profile['displayName'];
                    // }

                } elseif ($event instanceof StickerMessage) {
                    $this->bot->replyMessage(
                        $event->getReplyToken(),
                        new StickerMessageBuilder($event->getPackageId(), $event->getStickerId())
                    );
                }
            }
        }
    }
}
