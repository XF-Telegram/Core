<?php
namespace Kruzya\Telegram\Objects;

/**
 * Contains data required for decrypting and
 * authenticating EncryptedPassportElement. See the
 * Telegram Passport Documentation for a complete
 * description of the data decryption and
 * authentication processes.
 */
class EncryptedCredentials extends AbstractObjects {
  /**
   * Base64-encoded encrypted JSON-serialized data
   * with unique user's payload, data hashes and
   * secrets required for EncryptedPassportElement
   * decryption and authentication
   *
   * @var string
   */
  public $data;

  /**
   * Base64-encoded data hash for data authentication
   *
   * @var string
   */
  public $hash;

  /**
   * Base64-encoded secret, encrypted with the bot's
   * public RSA key, required for data decryption
   *
   * @var string
   */
  public $secret;

  protected function getRemappings() {
    return [
      'Data'    => 'data',
      'Hash'    => 'hash',
      'Secret'  => 'secret',
    ];
  }

  protected function getClassMaps() {
    return [];
  }
}