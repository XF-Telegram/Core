<?php
namespace Kruzya\Telegram\Objects;

/**
 * This object represent a user's profile pictures.
 */
class UserProfilePhotos extends AbstractObject {
  /**
   * Total number of profile pictures the target user
   * has
   *
   * @var integer
   */
  public $total_count;

  /**
   * Requested profile pictures (in up to 4 sizes
   * each)
   *
   * @var \Kruzya\Telegram\Objects\PhotoSize[]
   */
  public $photos;

  protected function getRemappings() {
    return [
      'TotalCount'  => 'total_count',
      'Photos'      => 'photos',
    ];
  }

  protected function getClassMaps() {
    return [
      'photos'      => 'Kruzya\\Telegram\\Objects\\PhotoSize',
    ];
  }
}