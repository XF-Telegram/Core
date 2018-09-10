<?php
namespace Kruzya\Telegram\Objects;

/**
 * This object represents a point on the map.
 */
class Location extends AbstractObject {
  /**
   * Longitude as defined by sender
   *
   * @var float
   */
  public $longitude;

  /**
   * Latitude as defined by sender
   *
   * @var float
   */
  public $latitude;

  protected function getRemappings() {
    return [
      'Longitude' => 'longitude',
      'Latitude'  => 'latitude',
    ];
  }

  protected function getClassMaps() {
    return [];
  }
}