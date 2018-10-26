<?php
namespace Kruzya\Telegram\Objects;

/**
 * Represents a link to an article or web page.
 */
class InlineQueryResultArticle extends AbstractInlineQueryResult {
  /**
   * Type of the result, must be article
   *
   * @var string
   */
  public $type = 'article';

  /**
   * Optional.
   * URL of the result
   *
   * @var string|null
   */
  public $url = null;

  /**
   * Optional.
   * Pass True, if you don't want the URL to be shown in the message
   *
   * @var boolean|null
   */
  public $hide_url = null;

  /**
   * Optional.
   * Short description of the result
   *
   * @var string|null
   */
  public $description = null;

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
      'URL'             => 'url',
      'HideURL'         => 'hide_url',
      'Description'     => 'description',
      'ThumbURL'        => 'thumb_url',
      'ThumbWidth'      => 'thumb_width',
      'ThumbHeight'     => 'thumb_height',
    ]);
  }
}