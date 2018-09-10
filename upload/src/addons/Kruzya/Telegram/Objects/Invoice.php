<?php
namespace Kruzya\Telegram\Objects;

/**
 * This object contains basic information about an
 * invoice.
 *
 * Attachments:
 * -> currencies.json
 *    https://core.telegram.org/bots/payments/currencies.json
 */
class Invoice extends AbstractObject {
  /**
   * Product name
   *
   * @var string
   */
  public $title;

  /**
   * Product description
   *
   * @var string
   */
  public $description;

  /**
   * Unique bot deep-linking parameter that can be
   * used to generate this invoice
   *
   * @var string
   */
  public $start_parameter;

  /**
   * Three-letter ISO 4217 currency code
   *
   * @var string
   */
  public $currency;

  /**
   * Price of the product in the smallest units
   * of the currency (integer, not float/double).
   * For example, for a price of US$ 1.45 pass amount
   * 145. See the exp parameter in currencies.json,
   * it shows the number of digits past the decimal
   * point for each currency (2 for the majority of
   * currencies).
   *
   * @var integer
   */
  public $total_amount;

  protected function getRemappings() {
    return [
      'Title'           => 'title',
      'Description'     => 'description',
      'StartParameter'  => 'start_parameter',
      'Currency'        => 'currency',
      'TotalAmount'     => 'total_amount',
    ];
  }

  protected function getClassMaps() {
    return [];
  }
}