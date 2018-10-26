<?php
namespace Kruzya\Telegram\Objects;

/**
 * Represents the content of a contact message to be
 * sent as the result of an inline query.
 */
class InputContactMessageContent extends AbstractInputMessageContent {
  /**
   * Contact's phone number
   *
   * @var string
   */
  public $phone_number;

  /**
   * Contact's first name
   *
   * @var string
   */
  public $first_name;

  /**
   * Optional.
   * Contact's last name
   *
   * @var string|null
   */
  public $last_name = null;

  /**
   * Optional.
   * Additional data about the contact in the form of
   * a vCard, 0-2048 bytes
   *
   * @var string|null
   */
  public $vcard = null;

  protected function getRemappings() {
    return [
      'PhoneNumber'     => 'phone_number',
      'FirstName'       => 'first_name',
      'LastName'        => 'last_name',

      'VCard'           => 'vcard',
      'vCard'           => 'vcard',
    ];
  }
}