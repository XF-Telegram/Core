<?php
namespace Kruzya\Telegram\Objects;

/**
 * Represents a link to an animated GIF file stored
 * on the Telegram servers. By default, this animated
 * GIF file will be sent by the user with an optional
 * caption. Alternatively, you can use
 * input_message_content to send a message with
 * specified content instead of the animation.
 */
class InlineQueryResultCachedGif extends AbstractInlineQueryCachedResult {
  /**
   * Type of the result, must be gif
   *
   * @var string
   */
  public $type = 'gif';

  /**
   * A valid file identifier for the GIF file
   *
   * @var string
   */
  public $gif_file_id;

  protected function getRemappings() {
    return array_merge(parent::getRemappings(), [
      'GIFFileID'   => 'gif_file_id',
    ]);
  }
}