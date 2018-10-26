<?php
namespace Kruzya\Telegram\Objects;

/**
 * Represents the content of a text message to be
 * sent as the result of an inline query.
 */
class InputTextMessageContent extends AbstractInputMessageContent {
  /**
   * Text of the message to be sent,
   * 1-4096 characters
   *
   * @var string
   */
  public $message_text;

  /**
   * Optional.
   * Send Markdown or HTML, if you want Telegram apps
   * to show bold, italic, fixed-width text or inline
   * URLs in your bot's message.
   *
   * @var string|null
   */
  public $parse_mode = null;

  /**
   * Optional.
   * Disables link previews for links in the sent
   * message
   *
   * @var boolean|null
   */
  public $disable_web_page_preview = null;

  protected function getRemappings() {
    return [
      'MessageText'           => 'message_text',
      'ParseMode'             => 'parse_mode',
      'DisableWebPagePreview' => 'disable_web_page_preview',
    ];
  }
}