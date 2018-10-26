<?php
namespace Kruzya\Telegram\Objects;

/**
 * This object represents a portion of the price for
 * goods or services.
 *
 * Attachments:
 * -> currencies.json
 *    https://core.telegram.org/bots/payments/currencies.json
 */
class LabeledPrice extends AbstractObject {
  /**
   * Portion label
   *
   * @var string
   */
  public $label;

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
  public $amount;

  protected function getRemappings() {
    return [
      'Label'   => 'label',
      'Amount'  => 'amount',
    ];
  }

  protected function getClassMaps() {
    return [];
  }
}