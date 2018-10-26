<?php
namespace Kruzya\Telegram\Objects;

/**
 * This object represents a shipping address.
 */
class ShippingAddress extends AbstractObject {
  /**
   * ISO 3166-1 alpha-2 country code
   *
   * @var string
   */
  public $country_code;

  /**
   * State, if applicable
   *
   * @var string
   */
  public $state;

  /**
   * City
   *
   * @var string
   */
  public $city;

  /**
   * First line for the address
   *
   * @var string
   */
  public $street_line1;

  /**
   * Second line for the address
   *
   * @var string
   */
  public $street_line2;

  /**
   * Address post code
   *
   * @var string
   */
  public $post_code;

  protected function getRemappings() {
    return [
      'CountryCode'   => 'country_code',
      'State'         => 'state',
      'City'          => 'city',
      'StreetLine1'   => 'street_line1',
      'StreetLine2'   => 'street_line2',
      'PostCode'      => 'post_code',
    ];
  }

  protected function getClassMaps() {
    return [];
  }
}