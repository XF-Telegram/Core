<?php
namespace Kruzya\Telegram;

class Hash {
  public static function isCorrectHash($id, array $data, $auth_date, $expect_hash) {
    return hash_equals($expect_hash, self::getHash($id, $data, $auth_date));
  }

  public static function getHash($id, array $data, $auth_date = null) {
    $data = self::prepareDataHash($id, $data, $auth_date);
    return hash_hmac('sha256', $data, self::getKey());
  }

  protected static function getKey() {
    return self::getSecretKey(Utils::getBotToken());
  }

  protected static function getSecretKey($token) {
    return hash('sha256', $token, true);
  }

  protected static function prepareDataHash($id, array $data, $auth_date = null) {
    $data['id'] = $id;

    if (empty($data['last_name']) || is_null($data['last_name']))
      unset($data['last_name']);
    if (empty($data['username']) || is_null($data['username']))
      unset($data['username']);
    if (empty($data['photo_url']) || is_null($data['photo_url']))
      unset($data['photo_url']);

    if ($auth_date !== null)
      $data['auth_date'] = $auth_date;
    else
      $data['auth_date'] = \XF::$time;

    $hashvar = [];
    foreach ($data as $key => $value)
      $hashvar[] = "{$key}={$value}";
    sort($hashvar);
    return implode("\n", $hashvar);
  }
}