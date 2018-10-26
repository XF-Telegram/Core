<?php
namespace Kruzya\Telegram\Objects;

/**
 * This object represents a file ready to be
 * downloaded. The file can be downloaded via the
 * special link (see notes). It is guaranteed that
 * the link will be valid for at least 1 hour. When
 * the link expires, a new one can be requested by
 * calling getFile.
 *
 * NOTE: Maximum file size to download is 20MB.
 *
 * NOTE 2: Link for download files is:
 * https://api.telegram.org/file/<token>/<file_path>
 */
class File extends AbstractObject {
  /**
   * Unique identifier for this file.
   *
   * @var string
   */
  public $file_id;

  /**
   * Optional.
   * File size, if known
   *
   * @var integer|null
   */
  public $file_size = null;

  /**
   * Optional.
   * File path.
   *
   * @var string|null
   */
  public $file_path = null;

  protected function getRemappings() {
    return [
      'FileID'    => 'file_id',
      'FileSize'  => 'file_size',
      'FilePath'  => 'file_path',
    ];
  }

  protected function getClassMaps() {
    return [];
  }
}