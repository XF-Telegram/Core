<?php
namespace Kruzya\Telegram\Objects;

/**
 * This object represents a phone contact.
 */
class Contact extends AbstractObject {
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
   * Contact's user identifier in Telegram
   *
   * @var integer|null
   */
  public $user_id = null;

  /**
   * Optional.
   * Additional data about the contact in the form of
   * a vCard
   *
   * @var string|null
   */
  public $vcard = null;

  protected function getRemappings() {
    return [
      'PhoneNumber'   => 'phone_number',
      'FirstName'     => 'first_name',
      'LastName'      => 'last_name',
      'UserID'        => 'user_id',
      'VCard'         => 'vcard',
    ];
  }

  protected function getClassMaps() {
    return [];
  }
}