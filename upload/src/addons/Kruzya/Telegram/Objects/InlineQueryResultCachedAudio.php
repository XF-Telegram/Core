<?php
namespace Kruzya\Telegram\Objects;

/**
 * Represents a link to an mp3 audio file stored on
 * the Telegram servers. By default, this audio file
 * will be sent by the user. Alternatively, you can
 * use input_message_content to send a message with
 * the specified content instead of the audio.
 *
 * NOTE: This will only work in Telegram versions
 *       released after 9 April, 2016. Older clients
 *       will ignore them.
 */
class InlineQueryResultCachedAudio extends AbstractInlineQueryCachedResult {
  /**
   * Type of the result, must be audio
   *
   * @var string
   */
  public $type = 'audio';

  /**
   * A valid file identifier for the audio file
   *
   * @var string
   */
  public $audio_file_id;

  protected function getRemappings() {
    return array_merge(parent::getRemappings(), [
      'AudioFileID' => 'audio_file_id',
    ]);
  }
}