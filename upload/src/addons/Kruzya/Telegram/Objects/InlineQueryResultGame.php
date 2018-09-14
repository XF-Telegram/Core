<?php
namespace Kruzya\Telegram\Objects;

/**
 * Represents a Game.
 *
 * NOTE: This will only work in Telegram versions
 *       released after October 1, 2016. Older
 *       clients will not display any inline
 *       results if a game result is among them.
 */
class InlineQueryResultGame extends AbstractInlineQueryResult {
  /**
   * Type of the result, must be game
   *
   * @var string
   */
  public $type = 'game';

  /**
   * Short name of the game
   *
   * @var string
   */
  public $game_short_name;

  protected function getRemappings() {
    $data = parent::getRemappings();

    unset($data['InputMessageContent']);
    unset($data['Title']);
    $data['GameShortName']  = 'game_short_name';

    return $data;
  }

  protected function getClassMaps() {
    $data = parent::getClassMaps();

    unset($data['input_message_content']);

    return $data;
  }
}