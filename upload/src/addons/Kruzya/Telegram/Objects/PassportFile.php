<?php
namespace Kruzya\Telegram\Objects;

/**
 * This object represents a file uploaded to Telegram
 * Passport. Currently all Telegram Passport files
 * are in JPEG format when decrypted and don't exceed
 * 10MB.
 */
class PassportFile extends AbstractObject {
  /**
   * Unique identifier for this file
   *
   * @var string
   */
  public $file_id;

  /**
   * File size
   *
   * @var integer
   */
  public $file_size;

  /**
   * Unix time when the file was uploaded
   *
   * @var integer
   */
  public $file_date;

  protected function getRemappings() {
    return [
      'FileID'    => 'file_id',
      'FileSize'  => 'file_size',
      'FileDate'  => 'file_date',
    ];
  }

  protected function getClassMaps() {
    return [];
  }
}