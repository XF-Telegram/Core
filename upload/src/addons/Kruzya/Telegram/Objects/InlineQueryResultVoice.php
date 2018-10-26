<?php
namespace Kruzya\Telegram\Objects;

/**
 * Represents a link to a voice recording in an .ogg
 * container encoded with OPUS. By default, this
 * voice recording will be sent by the user.
 * 
 * Alternatively, you can use input_message_content
 * to send a message with the specified content
 * instead of the the voice message.
 */
class InlineQueryResultVoice extends AbstractInlineQueryMediaResult {
  /**
   * Type of the result, must be voice
   *
   * @var string
   */
  public $type = 'voice';

  /**
   * A valid URL for the voice recording
   *
   * @var string
   */
  public $voice_url;

  protected function getRemappings() {
    return array_merge(parent::getRemappings(), [
      'VoiceURL'  => 'voice_url',
    ]);
  }
}