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

  public function __construct(App $app) {
    $this->app = $app;
  }

  public function handleWebhook(Request $request = null) {
    if ($request === null) 
      $request = $this->app->request();

    $body = $request->getInputRaw();
    if (empty($body)) {
      return;
    }

    $body = @json_decode($body);
    if (!$body) {
      return;
    }

    $this->handle($body);
  }

  public function handle(array $body) {
    $update_id = $body['update_id'];
    $fields = $this->getPossibleFields();

    foreach ($fields as $field => $callback) {
      if (!isset($body[$field]))
        continue;

      $content = $body[$field];
      $data = $this->prepare($callback);
      $data->exportData($content);

      $this->fire($data, $update_id);
    }
  }

  /**
   * For internal purposes.
   */
  protected function fire(Update $updateInstance, $updateId) {
    $hint = $updateInstance->getHint();
    $args = [$updateInstance, $updateId];

    return $this->app->fire('telegram_update_received', $args, $hint);
  }

  protected function prepare($className) {
    $className = $this->app->extendClass($className);
    return new $className($this->app);
  }

  protected function getPossibleFields() {
    return [
      'message'               =>  'Kruzya\\Telegram\\Update\\Message',
      'edited_message'        =>  'Kruzya\\Telegram\\Update\\EditedMessage',

      'channel_post'          =>  'Kruzya\\Telegram\\Update\\ChannelPost',
      'edited_channel_post'   =>  'Kruzya\\Telegram\\Update\\EditedChannelPost',

      'inline_query'          =>  'Kruzya\\Telegram\\Update\\InlineQuery',
      'chosen_inline_result'  =>  'Kruzya\\Telegram\\Update\\ChosenInlineQuery',

      'callback_query'        =>  'Kruzya\\Telegram\\Update\\CallbackQuery',
      'shipping_query'        =>  'Kruzya\\Telegram\\Update\\ShippingQuery',
      'pre_checkout_query'    =>  'Kruzya\\Telegram\\Update\\PreCheckoutQuery',
    ];
  }
}