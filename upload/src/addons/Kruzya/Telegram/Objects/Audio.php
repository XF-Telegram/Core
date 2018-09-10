<?php
namespace Kruzya\Telegram\Objects;

/**
 * This object represents an audio file to be treated
 * as music by the Telegram clients.
 */
class Audio extends AbstractObject {
  /**
   * Unique identifier for this file
   *
   * @var string
   */
  public $file_id;

  /**
   * Duration of the audio in seconds as defined by
   * sender
   *
   * @var integer
   */
  public $duration;

  /**
   * Optional.
   * Performer of the audio as defined by sender or
   * by audio tags
   *
   * @var string|null
   */
  public $performer = null;

  /**
   * Optional.
   * Title of the audio as defined by sender or by
   * audio tags
   *
   * @var string|null
   */
  public $title = null;

  /**
   * Optional.
   * MIME type of the file as defined by sender
   *
   * @var string|null
   */
  public $mime_type = null;

  /**
   * Optional.
   * File size
   *
   * @var integer|null
   */
  public $file_size = null;

  /**
   * Optional.
   * Thumbnail of the album cover to which the music
   * file belongs
   *
   * @var \Kruzya\Telegram\Objects\PhotoSize|null
   */
  public $thumb = null;

  protected function getRemappings() {
    return [
      'FileID'      => 'file_id',
      'Duration'    => 'duration',
      'Performer'   => 'performer',
      'Title'       => 'title',
      'MIMEType'    => 'mime_type',
      'FileSize'    => 'file_size',

      'Thumb'       => 'thumb',
      'Thumbnail'   => 'thumb',
      'AlbumCover'  => 'thumb',
    ];
  }

  protected function getClassMaps() {
    return [
      'thumb'       => 'Kruzya\\Telegram\\Objects\\PhotoSize',
    ];
  }
}