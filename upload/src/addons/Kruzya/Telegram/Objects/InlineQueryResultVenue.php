<?php
namespace Kruzya\Telegram\Objects;

/**
 * Represents a venue. By default, the venue will be
 * sent by the user. Alternatively, you can use
 * input_message_content to send a message with the
 * specified content instead of the venue.
 *
 * NOTE: This will only work in Telegram versions
 *       released after 9 April, 2016. Older clients
 *       will ignore them.
 */
class InlineQueryResultVenue extends AbstractInlineQueryResult {
  /**
   * Type of the result, must be venue
   *
   * @var string
   */
  public $type = 'venue';

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

  /**
   * Optional.
   * Url of the thumbnail for the result
   *
   * @var string|null
   */
  public $thumb_url = null;

  /**
   * Optional.
   * Thumbnail width
   *
   * @var integer|null
   */
  public $thumb_width = null;

  /**
   * Optional.
   * Thumbnail height
   *
   * @var integer|null
   */
  public $thumb_height = null;

  protected function getRemappings() {
    return array_merge(parent::getRemappings(), [
      'Latitude'        => 'latitude',
      'Longitude'       => 'longitude',
      'Address'         => 'address',

      'FoursquareID'    => 'foursquare_id',
      'FoursquareType'  => 'foursquare_type',

      'ThumbURL'        => 'thumb_url',
      'ThumbWidth'      => 'thumb_width',
      'ThumbHeight'     => 'thumb_height',
    ]);
  }
}