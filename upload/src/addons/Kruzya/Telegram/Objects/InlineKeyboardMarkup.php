<?php
namespace Kruzya\Telegram\Objects;

/**
 * This object represents an inline keyboard that
 * appears right next to the message it belongs to.
 *
 * NOTE: This will only work in Telegram versions
 * released after 9 April, 2016. Older clients will
 * display unsupported message.
 */
class InlineKeyboardMarkup extends AbstractObject {
  /**
   * Array of button rows, each represented by an
   * Array of InlineKeyboardButton objects
   *
   * @var \Kruzya\Telegram\Objects\InlineKeyboardButton[][]
   */
  public $inline_keyboard = [];

  protected function getRemappings() {
    return [
      'InlineKeyboard'  => 'inline_keyboard',
    ];
  }

  protected function getClassMaps() {
    return [
      'inline_keyboard' =>  'Kruzya\\Telegram\\Objects\\InlineKeyboardButton',
    ];
  }
}