<?php
namespace Kruzya\Telegram\Objects;

/**
 * Represents a location on a map. By default, the
 * location will be sent by the user. Alternatively,
 * you can use input_message_content to send a
 * message with the specified content instead of the
 * location.
 *
 * NOTE: This will only work in Telegram versions
 *       released after 9 April, 2016. Older clients
 *       will ignore them.
 */
class InlineQueryResultLocation extends AbstractInlineQueryResult {
  /**
   * Type of the result, must be location
   *
   * @var string
   */
  public $type = 'location';

  /**
   * Location latitude in degrees
   *
   * @var float
   */
  public $latitude;

  /**
   * Location longitude in degrees
   *
   * @var float
   */
  public $longitude;

  /**
   * Optional.
   * Period in seconds for which the location can be
   * updated, should be between 60 and 86400.
   *
   * @var integer|null
   */
  public $live_period = null;

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
      'Latitude'    => 'latitude',
      'Longitude'   => 'longitude',
      'LivePeriod'  => 'live_period',

      'ThumbURL'    => 'thumb_url',
      'ThumbWidth'  => 'thumb_width',
      'ThumbHeight' => 'thumb_height',
    ]);
  }
}