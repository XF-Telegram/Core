<?php
namespace Kruzya\Telegram\Objects;

/**
 * Contains information about why a request was
 * unsuccessful.
 */
class ResponseParameters extends AbstractObject {
  /**
   * Optional.
   * The group has been migrated to a supergroup with
   * the specified identifier. This number may be
   * greater than 32 bits and some programming
   * languages may have difficulty/silent defects in
   * interpreting it. But it is smaller than 52 bits,
   * so a signed 64 bit integer or double-precision
   * float type are safe for storing this identifier.
   *
   * @var integer|null
   */
  public $migrate_to_chat_id = null;

  /**
   * Optional.
   * In case of exceeding flood control, the number
   * of seconds left to wait before the request can
   * be repeated
   *
   * @var integer|null
   */
  public $retry_after = null;

  protected function getRemappings() {
    return [
      'MigrateToChatID' => 'migrate_to_chat_id',
      'RetryAfter'      => 'retry_after',
    ];
  }

  protected function getClassMaps() {
    return [];
  }
}