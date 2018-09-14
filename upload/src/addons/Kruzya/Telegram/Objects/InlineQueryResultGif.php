<?php
namespace Kruzya\Telegram\Objects;

/**
 * Represents a link to an animated GIF file. By
 * default, this animated GIF file will be sent by
 * the user with optional caption. Alternatively, you
 * can use input_message_content to send a message
 * with the specified content instead of the
 * animation.
 */
class InlineQueryResultGif extends AbstractInlineQueryMediaResult {
  /**
   * Type of the result, must be gif
   *
   * @var string
   */
  public $type = 'gif';

  /**
   * A valid URL for the GIF file. File size must not
   * exceed 1MB
   *
   * @var string
   */
  public $gif_url;

  /**
   * Optional.
   * Width of the GIF
   *
   * @var integer|null
   */
  public $gif_width = null;

  /**
   * Optional.
   * Height of the GIF
   *
   * @var integer|null
   */
  public $gif_height = null;

  /**
   * Optional.
   * Duration of the GIF
   *
   * @var integer|null
   */
  public $gif_duration = null;

  protected function getRemappings() {
    return array_merge(parent::getRemappings(), [
      'GIF_URL'       => 'gif_url',
      'GIF_Width'     => 'gif_width',
      'GIF_Height'    => 'gif_height',
      'GIF_Duration'  => 'gif_duration',
    ]);
  }
}