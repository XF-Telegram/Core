<?php
namespace Kruzya\Telegram\Objects;

/**
 * Represents a contact with a phone number. By
 * default, this contact will be sent by the user.
 * Alternatively, you can use input_message_content
 * to send a message with the specified content
 * instead of the contact.
 *
 * NOTE: This will only work in Telegram versions
 *       released after 9 April, 2016. Older clients
 *       will ignore them.
 */
class InlineQueryResultContact extends AbstractInlineQueryResult {
  /**
   * Type of the result, must be contact
   *
   * @var string
   */
  public $type = 'contact';

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

  /**
   * Optional.
   * Url of the thumbnail for the result
   *
   * @var string|null
   */
  public $thumb_url = null;

  /**
   * Optional.
   * Thumbnail width
   *
   * @var integer|null
   */
  public $thumb_width = null;

  /**
   * Optional.
   * Thumbnail height
   *
   * @var integer|null
   */
  public $thumb_height = null;

  protected function getRemappings() {
    return array_merge(parent::getRemappings(), [
      'PhoneNumber'       => 'phone_number',
      'FirstName'         => 'first_name',
      'LastName'          => 'last_name',

      'VCard'             => 'vcard',
      'vCard'             => 'vcard',

      'ThumbURL'          => 'thumb_url',
      'ThumbWidth'        => 'thumb_width',
      'ThumbHeight'       => 'thumb_height',
    ]);
  }
}