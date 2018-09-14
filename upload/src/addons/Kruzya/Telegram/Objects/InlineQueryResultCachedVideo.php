<?php
namespace Kruzya\Telegram\Objects;

/**
 * Represents a link to a video file stored on the
 * Telegram servers. By default, this video file will
 * be sent by the user with an optional caption.
 *
 * Alternatively, you can use input_message_content
 * to send a message with the specified content
 * instead of the video.
 */
class InlineQueryResultCachedVideo extends AbstractInlineQueryCachedResult {
  /**
   * Type of the result, must be video
   *
   * @var string
   */
  public $type = 'video';

  /**
   * A valid file identifier for the video file
   *
   * @var string
   */
  public $video_file_id;

  /**
   * Optional.
   * Short description of the result
   *
   * @var string|null
   */
  public $description = null;

  protected function getRemappings() {
    return array_merge(parent::getRemappings(), [
      'VideoFileID' => 'video_file_id',
      'Description' => 'description',
    ]);
  }
}