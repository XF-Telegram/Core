<?php
namespace Kruzya\Telegram\Objects;

/**
 * This object represents one button of the reply
 * keyboard. For simple text buttons String can be
 * used instead of this object to specify text of the
 * button. Optional fields are mutually exclusive.
 */
class KeyboardButton extends AbstractObject {
  /**
   * Text of the button. If none of the optional
   * fields are used, it will be sent as a message
   * when the button is pressed
   *
   * @var string
   */
  public $text;

  /**
   * Optional.
   * If True, the user's phone number will be sent as
   * a contact when the button is pressed. Available
   * in private chats only
   *
   * @var boolean|null
   */
  public $request_contact = null;

  /**
   * Optional.
   * If True, the user's current location will be
   * sent when the button is pressed. Available in
   * private chats only
   *
   * @var boolean|null
   */
  public $request_location = null;

  protected function getRemappings() {
    return [
      'Text'            => 'text',
      'RequestContact'  => 'request_contact',
      'RequestLocation' => 'request_location',
    ];
  }

  protected function getClassMaps() {
    return [];
  }
}