<?php
namespace Kruzya\Telegram\Objects;

/**
 * Contains information about Telegram Passport data
 * shared with the bot by the user.
 */
class PassportData extends AbstractObject {
  /**
   * Array with information about documents and other
   * Telegram Passport elements that was shared with
   * the bot
   *
   * @var \Kruzya\Telegram\Objects\EncryptedPassportElement[]
   */
  public $data = [];

  /**
   * Encrypted credentials required to decrypt the
   * data
   *
   * @var \Kruzya\Telegram\Objects\EncryptedCredentials
   */
  public $credentials;

  protected function getRemappings() {
    return [
      'Data'        => 'data',
      'Credentials' => 'credentials',
    ];
  }

  protected function getClassMaps() {
    return [
      'data'        => 'Kruzya\\Telegram\\Objects\\EncryptedPassportElement',
      'credentials' => 'Kruzya\\Telegram\\Objects\\EncryptedCredentials',
    ];
  }
}