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

    foreach ($fields as $field => $type) {
      if (!isset($body[$field]))
        continue;

      $content = $body[$field];
      $data = $this->prepare($type, $content);

      $this->fire($data, $field, $update_id);
    }
  }

  /**
   * For internal purposes.
   */
  protected function fire(AbstractObject $data, $hint, $updateId) {
    $args = [$data, $updateId];

    return $this->app->fire('telegram_update_received', $args, $hint);
  }

  protected function prepare($className, array $data) {
    $className = $this->app->extendClass($className);
    return call_user_func_array([$className, 'import'], [$data]);
  }

  protected function getPossibleFields() {
    return [
      'message'               =>  'Kruzya\\Telegram\\Objects\\Message',
      'edited_message'        =>  'Kruzya\\Telegram\\Objects\\Message',

      'channel_post'          =>  'Kruzya\\Telegram\\Objects\\Message',
      'edited_channel_post'   =>  'Kruzya\\Telegram\\Objects\\Message',

      'inline_query'          =>  'Kruzya\\Telegram\\Update\\InlineQuery',
      'chosen_inline_result'  =>  'Kruzya\\Telegram\\Update\\ChosenInlineQuery',

      'callback_query'        =>  'Kruzya\\Telegram\\Update\\CallbackQuery',
      'shipping_query'        =>  'Kruzya\\Telegram\\Update\\ShippingQuery',
      'pre_checkout_query'    =>  'Kruzya\\Telegram\\Update\\PreCheckoutQuery',
    ];
  }
}