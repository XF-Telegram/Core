<?php
namespace Kruzya\Telegram\Objects;

/**
 * Represents the content of a location message to be
 * sent as the result of an inline query.
 */
class InputLocationMessageContent extends AbstractInputMessageContent {
  /**
   * Latitude of the location in degrees
   *
   * @var float
   */
  public $latitude;

  /**
   * Longitude of the location in degrees
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

  protected function getRemappings() {
    return [
      'Latitude'      => 'latitude',
      'Longitude'     => 'longitude',
      'LivePeriod'    => 'live_period',
    ];
  }
}