<?php
namespace Kruzya\Telegram\Objects;

/**
 * This object represents one result of an media
 * inline query
 */
abstract class AbstractInlineQueryMediaResult extends AbstractInlineQueryResult {
  /**
   * URL of the thumbnail for the media content
   *
   * @var string
   */
  public $thumb_url;

  /**
   * Optional.
   * Caption of the media to be sent,
   * 0-200 characters
   *
   * @var string|null
   */
  public $caption = null;

  /**
   * Optional.
   * Send Markdown or HTML, if you want Telegram apps
   * to show bold, italic, fixed-width text or inline
   * URLs in the media caption.
   *
   * @var string|null
   */
  public $parse_mode = null;

  protected function getRemappings() {
    return array_merge(parent::getRemappings(), [
      'ThumbURL'      => 'thumb_url',
      'Caption'       => 'caption',
      'ParseMode'     => 'parse_mode',
    ]);
  }
}