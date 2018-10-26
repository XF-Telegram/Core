<?php
namespace Kruzya\Telegram\Objects;

/**
 * Represents a link to a video animation
 * (H.264/MPEG-4 AVC video without sound) stored on
 * the Telegram servers. By default, this animated
 * MPEG-4 file will be sent by the user with an
 * optional caption. Alternatively, you can use
 * input_message_content to send a message with the
 * specified content instead of the animation.
 */
class InlineQueryResultCachedMpeg4Gif extends AbstractInlineQueryCachedResult {
  /**
   * Type of the result, must be mpeg4_gif
   *
   * @var string
   */
  public $type = 'mpeg4_gif';

  /**
   * A valid file identifier for the MP4 file
   *
   * @var string
   */
  public $mpeg4_file_id;

  protected function getRemappings() {
    return array_merge(parent::getRemappings(), [
      'MPEG4FileID'   => 'mpeg4_file_id',
    ]);
  }
}