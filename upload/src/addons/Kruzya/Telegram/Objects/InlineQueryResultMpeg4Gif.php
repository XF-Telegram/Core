<?php
namespace Kruzya\Telegram\Objects;

/**
 * Represents a link to a video animation
 * (H.264/MPEG-4 AVC video without sound).
 *
 * By default, this animated MPEG-4 file will be sent
 * by the user with optional caption. Alternatively,
 * you can use input_message_content to send a
 * message with the specified content instead of the
 * animation.
 */
class InlineQueryResultMpeg4Gif extends AbstractInlineQueryMediaResult {
  /**
   * Type of the result, must be mpeg4_gif
   *
   * @var string
   */
  public $type = 'mpeg4_gif';

  /**
   * A valid URL for the MP4 file. File size must not
   * exceed 1MB
   *
   * @var string
   */
  public $mpeg4_url;

  /**
   * Optional.
   * Video width
   *
   * @var integer|null
   */
  public $mpeg4_width = null;

  /**
   * Optional.
   * Video height
   *
   * @var integer|null
   */
  public $mpeg4_height = null;

  /**
   * Optional.
   * Video duration
   *
   * @var integer|null
   */
  public $mpeg4_duration = null;

  protected function getRemappings() {
    return array_merge(parent::getRemappings(), [
      'MPEG4URL'        => 'mpeg4_url',
      'MPEG4Width'      => 'mpeg4_width',
      'MPEG4Height'     => 'mpeg4_height',
      'MPEG4Duration'   => 'mpeg4_duration',
    ]);
  }
}