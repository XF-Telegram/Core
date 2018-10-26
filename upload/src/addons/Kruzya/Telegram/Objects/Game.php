<?php
namespace Kruzya\Telegram\Objects;

/**
 * This object represents a game.
 *
 * Use BotFather to create and edit games, their
 * short names will act as unique identifiers.
 */
class Game extends AbstractObject {
  /**
   * Title of the game
   *
   * @var string
   */
  public $title;

  /**
   * Description of the game
   *
   * @var string
   */
  public $description;

  /**
   * Photo that will be displayed in the game message
   * in chats.
   *
   * @var \Kruzya\Telegram\Objects\PhotoSize[]
   */
  public $photo = [];

  /**
   * Optional.
   * Brief description of the game or high scores
   * included in the game message. Can be
   * automatically edited to include current high
   * scores for the game when the bot calls
   * setGameScore, or manually edited using
   * editMessageText.
   *
   * 0-4096 characters.
   *
   * @var string|null
   */
  public $text = null;

  /**
   * Optional.
   * Special entities that appear in text, such as
   * usernames, URLs, bot commands, etc.
   *
   * @var \Kruzya\Telegram\Objects\MessageEntity[]|null
   */
  public $text_entities = null;

  /**
   * Optional.
   * Animation that will be displayed in the game
   * message in chats. Upload via BotFather
   *
   * @var \Kruzya\Telegram\Objects\Animation|null
   */
  public $animation = null;

  protected function getRemappings() {
    return [
      'Title'         => 'title',
      'Description'   => 'description',
      'Photo'         => 'photo',

      'Text'          => 'text',
      'TextEntities'  => 'text_entities',

      'Animation'     => 'animation',
    ];
  }

  protected function getClassMaps() {
    return [
      'photo'         => 'Kruzya\\Telegram\\Objects\\PhotoSize',
      'text_entities' => 'Kruzya\\Telegram\\Objects\\MessageEntity',
      'animation'     => 'Kruzya\\Telegram\\Objects\\Animation'
    ];
  }
}