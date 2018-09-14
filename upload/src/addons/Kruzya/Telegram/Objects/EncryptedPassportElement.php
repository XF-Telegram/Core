<?php
namespace Kruzya\Telegram\Objects;

/**
 * Contains information about documents or other
 * Telegram Passport elements shared with the bot by
 * the user.
 */
class EncryptedPassportElement extends AbstractObject {
  /**
   * Element type. One of:
   * -> personal_details
   * -> passport
   * -> driver_license
   * -> identity_card
   * -> internal_passport
   * -> address
   * -> utility_bill
   * -> bank_statement
   * -> rental_agreement
   * -> passport_registration
   * -> temporary_registration
   * -> phone_number
   * -> email
   *
   * @var string
   */
  public $type;

  /**
   * Optional.
   * Base64-encoded encrypted Telegram Passport
   * element data provided by the user, available
   * for next types:
   * -> personal_details
   * -> passport
   * -> driver_license
   * -> identity_card
   * -> internal_passport
   * -> address
   * Can be decrypted and verified using the
   * accompanying EncryptedCredentials.
   *
   * @var string|null
   */
  public $data = null;

  /**
   * Optional.
   * User's verified phone number, available only for
   * "phone_number" type.
   *
   * @var string|null
   */
  public $phone_number = null;

  /**
   * Optional.
   * User's verified email address, available only
   * for "email" type.
   *
   * @var string|null
   */
  public $email = null;

  /**
   * Optional.
   * Array of encrypted files with documents
   * provided by the user, available for next types:
   * -> utility_bill
   * -> bank_statement
   * -> rental_agreement
   * -> passport_registration
   * -> temporary_registration
   * Files can be decrypted and verified using the
   * accompanying EncryptedCredentials.
   *
   * @var \Kruzya\Telegram\Objects\EncryptedCredentials[]|null
   */
  public $files = null;

  /**
   * Optional.
   * Encrypted file with the front side of the
   * document, provided by the user. Available for
   * next types:
   * -> passport
   * -> driver_license
   * -> identity_card
   * -> internal_passport
   * The file can be decrypted and verified using the
   * accompanying EncryptedCredentials.
   *
   * @var \Kruzya\Telegram\Objects\PassportFile|null
   */
  public $front_side = null;

  /**
   * Optional.
   * Encrypted file with the reverse side of the
   * document, provided by the user. Available for
   * next types: 
   * -> driver_license
   * -> identity_card
   * The file can be decrypted and verified using
   * the accompanying EncryptedCredentials.
   *
   * @var \Kruzya\Telegram\Objects\PassportFile|null
   */
  public $reverse_side = null;

  /**
   * Optional.
   * Encrypted file with the selfie of the user
   * holding a document, provided by the user;
   * available only for next types:
   * -> passport
   * -> driver_license
   * -> identity_card
   * -> internal_passport
   * The file can be decrypted and verified using the
   * accompanying EncryptedCredentials.
   *
   * @var \Kruzya\Telegram\Objects\PassportFile|null
   */
  public $selfie = null;

  /**
   * Optional.
   * Array of encrypted files with translated
   * versions of documents provided by the user.
   * Available if requested for next types:
   * -> passport
   * -> driver_license
   * -> identity_card
   * -> internal_passport
   * -> utility_bill
   * -> bank_statement
   * -> rental_agreement
   * -> passport_registration
   * -> temporary_registration
   * Files can be decrypted and verified using the
   * accompanying EncryptedCredentials.
   *
   * @var \Kruzya\Telegram\Objects\PassportFile[]|null
   */
  public $translation = null;

  /**
   * Base64-encoded element hash for using in
   * PassportElementErrorUnspecified
   *
   * @var string
   */
  public $hash;

  protected function getRemappings() {
    return [
      'Type'          => 'type',
      'Data'          => 'data',

      'PhoneNumber'   => 'phone_number',
      'EMail'         => 'email',
      'Files'         => 'files',
      'FrontSide'     => 'front_side',
      'ReverseSide'   => 'reverse_side',
      'Selfie'        => 'selfie',
      'Translation'   => 'translation',

      'Hash'          => 'hash',
    ];
  }

  protected function getClassMaps() {
    return [
      'files'         => 'Kruzya\\Telegram\\Objects\\PassportFile',
      'front_side'    => 'Kruzya\\Telegram\\Objects\\PassportFile',
      'reverse_side'  => 'Kruzya\\Telegram\\Objects\\PassportFile',
      'selfie'        => 'Kruzya\\Telegram\\Objects\\PassportFile',
      'translation'   => 'Kruzya\\Telegram\\Objects\\PassportFile',
    ];
  }
}