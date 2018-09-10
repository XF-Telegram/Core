<?php
namespace Kruzya\Telegram\Objects;

/**
 * This object contains basic information about a
 * successful payment.
 *
 * Attachments:
 * -> currencies.json
 *    https://core.telegram.org/bots/payments/currencies.json
 */
class SuccessfulPayment extends AbstractObject {
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
   * @var string
   */
  public $shipping_option_id = null;

  /**
   * Optional.
   * Order info provided by the user
   *
   * @var \Kruzya\Telegram\Objects\OrderInfo|null
   */
  public $order_info = null;

  /**
   * Telegram payment identifier
   *
   * @var string
   */
  public $telegram_payment_charge_id;

  /**
   * Provider payment identifier
   *
   * @var string
   */
  public $provider_payment_charge_id;

  protected function getRemappings() {
    return [
      'Currency'                => 'currency',
      'TotalAmount'             => 'total_amount',
      'InvoicePayload'          => 'invoice_payload',
      'ShippingOptionID'        => 'shipping_option_id',

      'OrderInfo'               => 'order_info',

      'TelegramPaymentChargeID' => 'telegram_payment_charge_id',
      'ProviderPaymentChargeID' => 'provider_payment_charge_id',
    ];
  }

  protected function getClassMaps() {
    return [
      'order_info'              => 'Kruzya\\Telegram\\Objects\\OrderInfo',
    ];
  }
}