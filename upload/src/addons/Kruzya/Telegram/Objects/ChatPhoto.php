<?php
namespace Kruzya\Telegram\Objects;

/**
 * This object represents a chat photo.
 */
class ChatPhoto extends AbstractObject {
  /**
   * Unique file identifier of small (160x160) chat
   * photo. This file_id can be used only for photo
   * download.
   *
   * @var string
   */
  public $small_file_id;

  /**
   * Unique file identifier of big (640x640) chat
   * photo. This file_id can be used only for photo
   * download.
   *
   * @var string
   */
  public $big_file_id;

  protected function getRemappings() {
    return [
      'SmallFileID' => 'small_file_id',
      'BigFileID'   => 'big_file_id',
    ];
  }

  protected function getClassMaps() {
    return [];
  }
}