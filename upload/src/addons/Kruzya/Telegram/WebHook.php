<?php
namespace Kruzya\Telegram;

use XF\App;
use XF\Http\Request;

use Kruzya\Telegram\AbstractUpdate as Update;

class WebHook {
  /**
   * @var \XF\App
   */
  protected $app;

  /**
   * @var \Kruzya\Telegram\UpdateManager
   */
  protected $manager;

  public function __construct(App $app) {
    $this->app = $app;

    $className = $this->app->extendClass('Kruzya\\Telegram\\UpdateManager');
    $this->manager = new $className($this->app);
  }

  public function handleWebhook(Request $request = null) {
    if ($request === null) 
      $request = $this->app->request();

    $key = $this->getSecretKey($request);
    if ($this->manager->isValidSecretKey($key))
      $this->handle($request);
  }

  /**
   * For internal purposes.
   */
  protected function getSecretKey(Request $request) {
    $data = [];
    $query_string = $request->getServer('QUERY_STRING', '');

    parse_str($query_string, $data);

    if (isset($data['_xfTelegramKey']))
      return $data['_xfTelegramKey'];
    return '';
  }

  protected function handle(Request $request) {
    $body = $request->getInputRaw();
    if (empty($body)) {
      return;
    }

    $body = @json_decode($body);
    if (!$body) {
      return;
    }

    $this->manager->handle($body);
  }
}