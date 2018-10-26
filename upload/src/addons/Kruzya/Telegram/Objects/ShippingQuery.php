<?php
namespace Kruzya\Telegram\Objects;

/**
 * This object contains information about an incoming
 * shipping query.
 */
class ShippingQuery extends AbstractObject {
  /**
   * Unique query identifier
   *
   * @var string
   */
  public $id;

  /**
   * User who sent the query
   *
   * @var \Kruzya\Telegram\Objects\User
   */
  public $from;

  /**
   * Bot specified invoice payload
   *
   * @var string
   */
  public $invoice_payload;

  /**
   * User specified shipping address
   *
   * @var \Kruzya\Telegram\Objects\ShippingAddress
   */
  public $shipping_address;

  protected function getRemappings() {
    return [
      'ID'                => 'id',
      'From'              => 'from',
      'InvoicePayload'    => 'invoice_payload',
      'ShippingAddress'   => 'shipping_address',
    ];
  }

  protected function getClassMaps() {
    return [
      'from'              => 'Kruzya\\Telegram\\Objects\\User',
      'shipping_address'  => 'Kruzya\\Telegram\\Objects\\ShippingAddress',
    ];
  }
}