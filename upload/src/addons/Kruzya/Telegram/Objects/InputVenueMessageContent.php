<?php
namespace Kruzya\Telegram\Objects;

/**
 * Represents the content of a venue message to be
 * sent as the result of an inline query.
 */
class InputVenueMessageContent extends AbstractInputMessageContent {
    /**
   * Latitude of the venue location in degrees
   *
   * @var float
   */
  public $latitude;

  /**
   * Longitude of the venue location in degrees
   *
   * @var float
   */
  public $longitude;

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
   * Foursquare identifier of the venue if known
   *
   * @var string|null
   */
  public $foursquare_id = null;

  /**
   * Optional.
   * Foursquare type of the venue, if known. (For
   * example, "arts_entertainment/default",
   * "arts_entertainment/aquarium" or
   * "food/icecream".)
   *
   * @var string|null
   */
  public $foursquare_type = null;

  protected function getRemappings() {
    return [
      'Latitude'        => 'latitude',
      'Longitude'       => 'longitude',
      'Title'           => 'title',
      'Address'         => 'address',

      'FoursquareID'    => 'foursquare_id',
      'FoursquareType'  => 'foursquare_type',
    ];
  }
}