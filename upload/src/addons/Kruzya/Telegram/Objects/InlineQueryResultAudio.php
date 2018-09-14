<?php
namespace Kruzya\Telegram\Objects;

/**
 * Represents a link to an mp3 audio file. By
 * default, this audio file will be sent by the user.
 *
 * Alternatively, you can use input_message_content
 * to send a message with the specified content
 * instead of the audio.
 *
 * NOTE: This will only work in Telegram versions
 *       released after 9 April, 2016. Older clients
 *       will ignore them.
 */
class InlineQueryResultAudio extends AbstractInlineQueryResult {
  /**
   * Type of the result, must be audio
   *
   * @var string
   */
  public $type = 'audio';

  /**
   * A valid URL for the audio file
   *
   * @var string
   */
  public $audio_url;

  /**
   * Optional.
   * Caption, 0-200 characters
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

  /**
   * Optional.
   * Performer
   *
   * @var string|null
   */
  public $performer = null;

  /**
   * Optional.
   * Audio duration in seconds
   *
   * @var integer|null
   */
  public $audio_duration = null;

  protected function getRemappings() {
    return array_merge(parent::getRemappings(), [
      'AudioURL'        => 'audio_url',
      'AudioDuration'   => 'audio_duration',

      'Caption'         => 'caption',
      'ParseMode'       => 'parse_mode',

      'Performer'       => 'performer',
    ]);
  }
}