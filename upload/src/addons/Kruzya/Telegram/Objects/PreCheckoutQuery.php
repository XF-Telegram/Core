<?php
namespace Kruzya\Telegram\Objects;

/**
 * This object contains information about an incoming
 * pre-checkout query.
 *
 * Attachments:
 * -> currencies.json
 *    https://core.telegram.org/bots/payments/currencies.json
 */
public PreCheckoutQuery extends AbstractObject {
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
   * Three-letter ISO 4217 currency code
   *
   * @var string
   */
  public $currency;

  /**
   * Total price in the smallest units of the
   * currency (integer, not float/double). For
   * example, for a price of US$ 1.45 pass amount
   * 145. See the exp parameter in currencies.json,
   * it shows the number of digits past the decimal
   * point for each currency (2 for the majority of
   * currencies).
   *
   * @var integer
   */
  public $total_amount;

  /**
   * Bot specified invoice payload
   *
   * @var string
   */
  public $invoice_payload;

  /**
   * Optional.
   * Identifier of the shipping option chosen by the
   * user
   *
   * @var string|null
   */
  public $shipping_option_id = null;

  /**
   * Optional.
   * Order info provided by the user
   *
   * @var \Kruzya\Telegram\Objects\OrderInfo
   */
  public $order_info = null;

  protected function getRemappings() {
    return [
      'ID'                => 'id',
      'From'              => 'from',
      'Currency'          => 'currency',
      'TotalAmount'       => 'total_amount',
      'InvoicePayload'    => 'invoice_payload',
      'ShippingOptionID'  => 'shipping_option_id',
      'OrderInfo'         => 'order_info',
    ];
  }

  protected function getClassMaps() {
    return [
      'from'              => 'Kruzya\\Telegram\\Objects\\User',
      'order_info'        => 'Kruzya\\Telegram\\Objects\\OrderInfo',
    ];
  }
}