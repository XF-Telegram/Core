<?php
namespace Kruzya\Telegram\Objects;

/**
 * This object represents information about an order.
 */
class OrderInfo extends AbstractObject {
  /**
   * Optional.
   * User name
   *
   * @var string|null
   */
  public $name = null;

  /**
   * Optional.
   * User's phone number
   *
   * @var string|null
   */
  public $phone_number = null;

  /**
   * Optional.
   * User email
   *
   * @var string|null
   */
  public $email = null;

  /**
   * Optional.
   * User shipping address
   *
   * @var \Kruzya\Telegram\Objects\ShippingAddress|null
   */
  public $shipping_address = null;

  protected function getRemappings() {
    return [
      'Name'            => 'name',
      'PhoneNumber'     => 'phone_number',

      'E_Mail'          => 'email',
      'EMail'           => 'email',

      'ShippingAddress' => 'shipping_address',
    ];
  }

  protected function getClassMaps() {
    return [
      'shipping_address'  => 'Kruzya\\Telegram\\Objects\\ShippingAddress',
    ];
  }
}