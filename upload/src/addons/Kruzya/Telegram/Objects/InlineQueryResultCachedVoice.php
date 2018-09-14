<?php
namespace Kruzya\Telegram\Objects;

/**
 * Represents a link to a voice message stored on the
 * Telegram servers. By default, this voice message
 * will be sent by the user. Alternatively, you can
 * use input_message_content to send a message with
 * the specified content instead of the voice
 * message.
 *
 * NOTE: This will only work in Telegram versions
 *       released after 9 April, 2016. Older clients
 *       will ignore them.
 */
class InlineQueryResultCachedVoice extends AbstractInlineQueryCachedResult {
  /**
   * Type of the result, must be voice
   *
   * @var string
   */
  public $type = 'voice';

  /**
   * A valid file identifier for the voice message
   *
   * @var string
   */
  public $voice_file_id;

  protected function getRemappings() {
    return array_merge(parent::getRemappings(), [
      'VoiceFileID' => 'voice_file_id',
    ]);
  }
}