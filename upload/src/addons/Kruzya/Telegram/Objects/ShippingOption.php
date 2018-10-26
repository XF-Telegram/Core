<?php
namespace Kruzya\Telegram\Objects;

/**
 * This object represents one shipping option.
 */
class ShippingOption extends AbstractObject {
  /**
   * Shipping option identifier
   *
   * @var string
   */
  public $id;

  /**
   * Option title
   *
   * @var string
   */
  public $title;

  /**
   * List of price portions
   *
   * @var \Kruzya\Telegram\Objects\LabeledPrice[]
   */
  public $prices = [];

  protected function getRemappings() {
    return [
      'ID'      => 'id',
      'Title'   => 'title',
      'Prices'  => 'prices',
    ];
  }

  protected function getClassMaps() {
    return [
      'prices'  => 'Kruzya\\Telegram\\Objects\\LabeledPrice',
    ];
  }
}