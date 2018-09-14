<?php
namespace Kruzya\Telegram;

class UpdateManager {
  /**
   * @var \XF\App
   */
  protected $app;

  public function __construct(App $app) {
    $this->app = $app;
  }

  public function updateMode() {
    $secretKey = $this->getSecretKey();

    $mode = $this->app->options()->telegramGetUpdates;
    $url  = $this->app->options()->boardUrl . "/telegram.php?_xfTelegramKey={$secretKey}";

    $params = [
      'url' => '',
    ];

    if ($mode === 'webhook') {
      $params['url'] = $url;
      $params['max_connections'] = 25;
    }

    Utils::api()->setWebhook($params);
  }

  public function getUpdates() {
    if ($this->app->options()->telegramGetUpdates != 'longpoll') {
      return;
    }

    $registry = $this->app->registry();
    if ($registry['tg_botActivePoll'] == 1)
      return;

    $id = 0;
    if (isset($registry['tg_botUpdateId']))
      $id = $registry['tg_botUpdateId'];

    $registry['tg_botActivePoll'] = 1;
    $response = Utils::api()->getUpdates([
      'offset'  => $id,
      'timeout' => 30,
    ]);
    $registry['tg_botActivePoll'] = 0;

    $this->handle($response);
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

  public function isValidSecretKey($key) {
    return $key === $this->getSecretKey();
  }

  /**
   * For internal purposes.
   */
  protected function getSecretKey() {
    $botToken = Utils::getBotToken();
    $botName  = Utils::getBotName();

    return crypt($botName, $botToken);
  }

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

      'inline_query'          =>  'Kruzya\\Telegram\\Objects\\InlineQuery',
      'chosen_inline_result'  =>  'Kruzya\\Telegram\\Objects\\ChosenInlineResult',

      'callback_query'        =>  'Kruzya\\Telegram\\Objects\\CallbackQuery',
      'shipping_query'        =>  'Kruzya\\Telegram\\Objects\\ShippingQuery',
      'pre_checkout_query'    =>  'Kruzya\\Telegram\\Objects\\PreCheckoutQuery',
    ];
  }
}