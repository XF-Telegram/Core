<?php
namespace Kruzya\Telegram;

use XF\Util\Hash;

class DirectAuth {
  public static function onGotMessage(Objects\Message $message, $update_id) {
    if ($message->Chat->Type !== 'private')
      return;

    $text = $message->Text;
    if (!self::startsWith($text, '/start ')) {
      return;
    }

    $token = Hash::hashText(str_replace('/start ', '', $text), 'sha256');

    /** @var \Kruzya\Telegram\Objects\User */
    $user = $message->From;
    $data = [
      'id'          => $user->ID,
      'first_name'  => $user->FirstName,
      'last_name'   => $user->LastName,
      'username'    => $user->Username,
      'auth_date'   => \XF::$time,
      'hash'        => $token,
    ];

    $redirectTo = \XF::app()->options()->boardUrl . '/connected_account.php?' . http_build_query($data);

    Utils::api()->sendMessage([
      'chat_id'                   => $user->ID,
      'text'                      => "<a href='{$redirectTo}'>Continue login</a>",
      'parse_mode'                => 'HTML',
      'disable_web_page_preview'  => true,
    ]);
  }

  protected static function startsWith($haystack, $needle) {
    $strLength = strlen($needle);
    return (substr($haystack, 0, $strLength) === $needle);
  }
}