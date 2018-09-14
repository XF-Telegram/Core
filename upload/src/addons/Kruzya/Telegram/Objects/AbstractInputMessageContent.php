<?php
namespace Kruzya\Telegram\Objects;

/**
 * This object represents the content of a message to
 * be sent as a result of an inline query.
 */
abstract AbstractInputMessageContent extends AbstractObject {
  protected function getRemappings() {
    return [];
  }

  protected function getClassMaps() {
    return [];
  }
}