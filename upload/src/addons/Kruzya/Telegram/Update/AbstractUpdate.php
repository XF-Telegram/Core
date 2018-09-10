<?php
namespace Kruzya\Telegram\Update;

use XF\App;

abstract class AbstractUpdate {
  /**
   * @var \XF\App
   */
  protected $app;

  public function __construct(App $app) {
    $this->app = $app;
  }

  public function exportData(array $data);
}