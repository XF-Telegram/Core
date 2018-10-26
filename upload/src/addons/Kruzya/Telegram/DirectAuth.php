<?php
namespace Kruzya\Telegram;

class DirectAuth {
  public static function onGotMessage(Objects\Message $message, $update_id) {
    if ($message->Chat->Type !== 'private')
      return;

    if ($message->Text != '/start auth') {
      return;
    }

    /** @var \Kruzya\Telegram\Objects\User */
    $user = $message->From;

    $data = [
      'first_name'  => $user->FirstName,
      'last_name'   => $user->LastName,
      'username'    => $user->Username,
    ];

    $hash  = Hash::getHash($user->ID, $data, \XF::$time);

    $redirectTo = \XF::app()->options()->boardUrl . '/connected_account.php?' . http_build_query(array_merge($data, [
      'id'        => $user->ID,
      'auth_date' => \XF::$time,
      'hash'      => $hash,
    ]));

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