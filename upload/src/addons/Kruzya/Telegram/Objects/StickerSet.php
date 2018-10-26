<?php
namespace Kruzya\Telegram\Objects;

/**
 * This object represents a sticker set.
 */
class StickerSet extends AbstractObject {
  /**
   * Sticker set name
   *
   * @var string
   */
  public $name;

  /**
   * Sticker set title
   *
   * @var string
   */
  public $title;

  /**
   * True, if the sticker set contains masks
   *
   * @var boolean
   */
  public $contains_masks = false;

  /**
   * List of all set stickers
   *
   * @var \Kruzya\Telegram\Objects\Sticker[]
   */
  public $stickers = [];

  protected function getRemappings() {
    return [
      'Name'          => 'name',
      'Title'         => 'title',
      'ContainsMasks' => 'contains_masks',
      'Stickers'      => 'stickers',
    ];
  }

  protected function getClassMaps() {
    return [
      'stickers'      => 'Kruzya\\Telegram\\Objects\\Sticker',
    ];
  }
}