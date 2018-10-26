<?php
namespace Kruzya\Telegram\Objects;

/**
 * Represents a link to a page containing an embedded
 * video player or a video file. By default, this
 * video file will be sent by the user with an
 * optional caption. Alternatively, you can use
 * input_message_content to send a message with the
 * specified content instead of the video.
 *
 * NOTE: If an InlineQueryResultVideo message
 *       contains an embedded video (e.g.,
 *       YouTube), you must replace its content using
 *       input_message_content.
 */
class InlineQueryResultVideo extends AbstractInlineQueryMediaResult {
  /**
   * Type of the result, must be video
   *
   * @var string
   */
  public $type = 'video';
  /**
   * A valid URL for the embedded video player or
   * video file
   *
   * @var string
   */
  public $video_url;

  /**
   * Mime type of the content of video url,
   * "text/html" or "video/mp4"
   *
   * @var string
   */
  public $mime_type;

  /**
   * Optional.
   * Video width
   *
   * @var integer|null
   */
  public $video_width = null;

  /**
   * Optional.
   * Video height
   *
   * @var integer|null
   */
  public $video_height = null;

  /**
   * Optional.
   * Video duration in seconds
   *
   * @var integer|null
   */
  public $video_duration = null;

  protected function getRemappings() {
    return array_merge(parent::getRemappings(), [
      'VideoURL'        => 'video_url',
      'MIMEType'        => 'mime_type',

      'VideoWidth'      => 'video_width',
      'VideoHeight'     => 'video_height',
      'VideoDuration'   => 'video_duration',
    ]);
  }
}