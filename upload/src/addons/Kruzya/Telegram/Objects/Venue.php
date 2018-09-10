<?php
namespace Kruzya\Telegram\Objects;

/**
 * This object represents a venue.
 */
class Venue extends AbstractObject {
  /**
   * Venue location
   *
   * @var \Kruzya\Telegram\Objects\Location
   */
  public $location;

  /**
   * Name of the venue
   *
   * @var string
   */
  public $title;

  /**
   * Address of the venue
   *
   * @var string
   */
  public $address;

  /**
   * Optional.
   * Foursquare identifier of the venue
   *
   * @var string|null
   */
  public $foursquare_id = null;

  /**
   * Optional.
   * Foursquare type of the venue. (For example,
   * "arts_entertainment/default",
   * "arts_entertainment/aquarium" or
   * "food/icecream".)
   *
   * @var string|null
   */
  public $foursquare_type = null;

  protected function getRemappings() {
    return [
      'Location'        => 'location',
      'Title'           => 'title',
      'Address'         => 'address',

      'FoursquareID'    => 'foursquare_id',
      'FoursquareType'  => 'foursquare_type',
    ];
  }

  protected function getClassMaps() {
    return [
      'location'        => 'Kruzya\\Telegram\\Objects\\Location',
    ];
  }
}