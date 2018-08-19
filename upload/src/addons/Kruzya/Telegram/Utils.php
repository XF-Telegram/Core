<?php
namespace Kruzya\Telegram;

use Kruzya\Telegram\Entity\User as TGUser;
use XF\Entity\User as XFUser;

class Utils {
  private static $_botToken   = NULL;
  private static $_settings   = NULL;
  private static $_apiwrapper = NULL;

  private static function getSettings() {
    if (self::$_settings === NULL) {
      $provider = \XF::finder('XF:ConnectedAccountProvider')->where('provider_id', 'telegram')->fetchOne();
      if (!$provider)
        throw new \LogicException('Account provider not registered');

      self::$_settings = $provider->options;
    }

    return self::$_settings;
  }

  private static function getSetting($key, $default = null) {
    $settings = self::getSettings();
    return isset($settings[$key]) ? $settings[$key] : $default;
  }

  public static function getBotToken() {
    return self::getSetting('bot_token', '');
  }

  public static function getBotName() {
    return self::getSetting('bot_name', '');
  }

  public static function getApiResponse($method, $body = []) {
    return call_user_func_array([self::api(), $method], [$body]);
  }

  public static function getTelegramEntityByUser(XFUser $user) {
    /** @var \XF\Entity\UserConnectedAccount ConnectedAccount */
    foreach ($user->ConnectedAccounts as $ConnectedAccount) {
      if ($ConnectedAccount->provider != 'telegram')
        continue;

      /** @var int userid */
      $userid = $ConnectedAccount->provider_key;

      return \XF::app()->em()->find('Kruzya\\Telegram:User', $userid);
    }

    return NULL;
  }

  public static function api() {
    if (self::$_apiwrapper === NULL) {
      $token = self::getBotToken();
      $proxy = NULL;

      $options = \XF::app()->options();
      if ($options->telegramUseProxy) {
        // get all settings.
        $address  = $options->telegramProxyAddress;
        $login    = $options->telegramProxyLogin;
        $password = $options->telegramProxyPassword;

        // check login exist.
        if (empty($login))
          $login = 'anonymous';
        $credentials = $login;

        // check password exist.
        if (!empty($password))
          $credentials .= ":{$password}";

        // add credentials to proxy address.
        $proxy = str_replace('://', "://$credentials@", $address);
      }

      // create a wrapper.
      self::$_apiwrapper = new API($token, $proxy);
    }

    return self::$_apiwrapper->setGlobalVariables([]);
  }
}
