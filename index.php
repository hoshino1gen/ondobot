<?php

ini_set('display_errors', 1);
ini_set('log_errors', 1);

date_default_timezone_set('Asia/Tokyo');

use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
use \LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use \LINE\LINEBot\Constant\HTTPHeader;

require_once __DIR__ . '/vendor/autoload.php';

$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('CHANNEL_ACCESS_TOKEN'));
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => getenv('CHANNEL_SECRET')]);

$signature = $_SERVER["HTTP_" . \LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];
try {
  $events = $bot->parseEventRequest(file_get_contents('php://input'), $signature);
} catch(\LINE\LINEBot\Exception\InvalidSignatureException $e) {
  error_log("parseEventRequest failed. InvalidSignatureException => ".var_export($e, true));
} catch(\LINE\LINEBot\Exception\UnknownEventTypeException $e) {
  error_log("parseEventRequest failed. UnknownEventTypeException => ".var_export($e, true));
} catch(\LINE\LINEBot\Exception\UnknownMessageTypeException $e) {
  error_log("parseEventRequest failed. UnknownMessageTypeException => ".var_export($e, true));
} catch(\LINE\LINEBot\Exception\InvalidEventRequestException $e) {
  error_log("parseEventRequest failed. InvalidEventRequestException => ".var_export($e, true));
}


foreach ($events as $event) {

  error_log('event event:'  . print_r( $event, true) );

  if (!($event instanceof \LINE\LINEBot\Event\MessageEvent)) {
    error_log('Non message event has come');
    continue;
  }

  if (!($event instanceof \LINE\LINEBot\Event\MessageEvent\TextMessage)) {
    error_log('Non text message has come');
    continue;
  }

    $SendMessage = new MultiMessageBuilder();

    $TextMessageBuilder = new TextMessageBuilder("今日の温度は、24度です。");
    $TextMessageBuilder1 = new TextMessageBuilder("今日の湿度は、湿度は75パーセントです。");
    $ImageMessageBuilder = new ImageMessageBuilder("https://ondobot.herokuapp.com/sample.png", "https://ondobot.herokuapp.com/sample.png");

    if ( $event->getText() === '温度' ) {
      $SendMessage->add($TextMessageBuilder);
//      $SendMessage->add($ImageMessageBuilder);
    } else {
      $SendMessage->add($TextMessageBuilder1);
    }

    $dummy = "ok";
    $bot->replyMessage($event->getReplyToken(), $SendMessage);
    error_log('error test:'  . print_r($dummy, true) );

}

 ?>