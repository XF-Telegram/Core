<?php
namespace Kruzya\Telegram;

use Kruzya\Telegram\Entity\User as TGUser;
use XF\Entity\User as XFUser;

use GuzzleHttp\Exception\RequestException;

class Utils {
  private static $_botToken = NULL;

  public static function getBotToken() {
    if (self::$_botToken === NULL) {
      $provider = \XF::finder('XF:ConnectedAccountProvider')->where('provider_id', 'telegram')->fetchOne();
      if (!$provider)
        throw new \LogicException('Account provider not registered');

      self::$_botToken = $provider->options['bot_token'];
    }

    return self::$_botToken;
  }

  public static function getApiResponse($method, $body = []) {
    $token    = self::getBotToken();
    $client   = \XF::app()->http()->client();

    try {
      $response = $client->post("https://api.telegram.org/bot{$token}/{$method}", [
        'json'        => $body,
      ]);

      return json_decode($response->getBody(), true);
    } catch (RequestException $e) {
      return json_decode($e->getResponse()->getBody(), true);
    }
  }

  public static function getTelegramEntityByUser(XFUser $user) {
    /** @var \XF\Entity\UserConnectedAccount ConnectedAccount */
    foreach ($user->ConnectedAccounts as $ConnectedAccount) {
      if ($ConnectedAccount->provider != 'telegram')
        continue;

      /** @var int userid */
      $userid = $ConnectedAccount->provider_key;

      return \XF::finder('Kruzya\\Telegram:User')
        ->where('id', $userid)
        ->fetchOne();
    }

    return NULL;
  }

  public static function purifyHtml($text) {
    $dom = new \DOMDocument('1.0', 'utf-8');
    if ($dom->loadHTML('<?xml encoding="UTF-8">' . $text)) {
      $itemsToDelete = [];
      $tagsToDelete = ['span', 'b', 'i', 'strong'];
      foreach ($dom->childNodes as $item) {
        if ($item->nodeType == XML_PI_NODE) {
          $dom->removeChild($item);
          break;
        }
      }

      foreach ($dom->getElementsByTagName('a') as $item) {
        $attributes = $item->attributes;
        $attributesToDelete = [];
        foreach ($attributes as $attr) {
          if ($attr->name != 'href') {
            $attributesToDelete[] = $attr->name;
          }
        }

        foreach ($attributesToDelete as $attr) {
          $item->removeAttribute($attr);
        }
      }

      $body = $dom->getElementsByTagName('body')->item(0);
      foreach ($tagsToDelete as $tag) {
        foreach ($dom->getElementsByTagName($tag) as $item) {
          $itemsToDelete[] = $item;
        }
      }

      foreach ($itemsToDelete as $item) {
        $parent = $item->parentNode;
        if ($parent != NULL) {
          $parent->removeChild($item);
        }
      }

      $text = $dom->saveHTML();
      $text = str_replace([
        '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">',
        '<html><body>', '</body></html>'
      ], '', $text);
      $text = trim($text);
    }

    return $text;
  }
}
