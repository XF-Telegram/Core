<?php
namespace Kruzya\Telegram\Objects;

/**
 * This object represents one row of the high scores
 * table for a game.
 */
class CallbackGame extends AbstractObject {
  /**
   * Position in high score table for the game
   *
   * @var integer
   */
  public $position;

  /**
   * User
   *
   * @var \Kruzya\Telegram\Objects\User
   */
  public $user;

  /**
   * Score
   *
   * @var integer
   */
  public $score;
  
  protected function getRemappings() {
    return [
      'Position'  => 'position',
      'User'      => 'user',
      'Score'     => 'score',
    ];
  }

  protected function getClassMaps() {
    return [
      'user'      => 'Kruzya\\Telegram\\Objects\\User',
    ];
  }
}