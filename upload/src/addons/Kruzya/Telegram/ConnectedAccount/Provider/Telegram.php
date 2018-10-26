<?php
namespace Kruzya\Telegram\ConnectedAccount\Provider;

use Kruzya\Telegram\AuthToken\TelegramToken;
use Kruzya\Telegram\Repository\User;

use XF\ConnectedAccount\Http\HttpResponseException;
use XF\ConnectedAccount\Provider\AbstractProvider;
use XF\ConnectedAccount\Storage\StorageState;

use XF\Mvc\Controller;
use XF\Entity\ConnectedAccountProvider;
use XF\Http\Request;

use XF\Util\Hash;
use XF\Util\Random;

class Telegram extends AbstractProvider {
  public function getOAuthServiceName() {
    return 'Telegram';
  }

  public function getProviderDataClass() {
    return 'Kruzya\\Telegram:ProviderData\\' . $this->getOAuthServiceName();
  }

  public function getDefaultOptions() {
    return [
      'bot_name'            => '',
      'bot_token'           => '',
    ];
  }

  public function getOAuthConfig(ConnectedAccountProvider $provider, $redirectUri = null) {
    return [
      'bot_name'            => $provider->options['bot_name'],
      'bot_token'           => $provider->options['bot_token'],
    ];
  }

  public function handleAuthorization(Controller $controller, ConnectedAccountProvider $provider, $returnUrl) {
    $app = \XF::app();
    $session = $app['session.public'];
    $auth_method = $app->options()->telegramAuthMethod;
    $session->set('connectedAccountRequest', [
      'provider'  => $this->providerId,
      'returnUrl' => $returnUrl,
      'test'      => $this->testMode,
      'method'    => $auth_method,
      'privKey'   => Hash::hashText(Random::getRandomBytes(12), 'sha256'),
    ]);
    $session->save();

    if ($auth_method === 'oauth')
      return $this->handleOAuthAuthorization($controller, $provider);
    else if ($auth_method === 'direct')
      return $this->handleDirectAuthorization($controller, $provider);
  }

  public function requestProviderToken(StorageState $storageState, Request $request, &$error = null, $skipStoredToken = false) {
    $id = $request->get('id');

    $data = [
      'first_name'  => $request->get('first_name'),
      'last_name'   => $request->get('last_name', ''),
      'username'    => $request->get('username', ''),
      'photo_url'   => $request->get('photo_url', ''),
    ];

    $auth_date  = $request->get('auth_date');
    $hash       = $request->get('hash');

    $sessData = \XF::app()['session.public']['connectedAccountRequest'];
    switch ($sessData['method']) {
      case 'oauth':
        if (!$this->isValidAuth($id, $data, $auth_date, $hash, $storageState, $error))
          return false;
        break;
      case 'direct':
        if ($hash !== Hash::hashText($sessData['privKey'], 'sha256')) {
          $error = \XF::phraseDeferred('error_occurred_while_connecting_with_x', ['provider' => $this->getTitle()]);
          return false;
        }
        break;
    }

    $token = new TelegramToken($id);

    $user = $this->user($id);
    $user->bulkSet($data);
    $user->updated = \XF::$time;
    $user->save();

    $storageState->storeToken($token);
    return $token;
  }

  /**
   * Checks for valid auth.
   */
  protected function isValidAuth($id, array $data, $auth_date, $hash, StorageState $storageState, &$error = null) {
    $secret_key = $this->getSecretKey($storageState->getProvider()->options['bot_token']);

    if (!$this->isCorrectHash($id, $data, $auth_date, $hash, $secret_key)) {
      $error = \XF::phraseDeferred('error_occurred_while_connecting_with_x', ['provider' => $this->getTitle()]);
      return false;
    }

    return true;
  }

  protected function isCorrectHash($id, array $data, $auth_date, $expect_hash, $key) {
    $data = $this->prepareDataHash($id, $data, $auth_date);
    $expect = hash_hmac('sha256', $data, $key);

    return hash_equals($expect_hash, $expect);
  }

  protected function getSecretKey($token) {
    return hash('sha256', $token, true);
  }

  protected function prepareDataHash($id, array $data, $auth_date = null) {
    $data['id'] = $id;

    if (empty($data['last_name']))
      unset($data['last_name']);
    if (empty($data['username']))
      unset($data['username']);
    if (empty($data['photo_url']))
      unset($data['photo_url']);

    if ($auth_date !== null)
      $data['auth_date'] = $auth_date;
    else
      $data['auth_date'] = \XF::$time;

    $hashvar = [];
    foreach ($data as $key => $value)
      $hashvar[] = "{$key}={$value}";
    sort($hashvar);
    return implode("\n", $hashvar);
  }

  /**
   * Auth methods
   */
  protected function handleOAuthAuthorization(Controller $controller, ConnectedAccountProvider $provider) {
    $data = [
      'bot_name'     => $provider->options['bot_name'],
      'redirect_uri' => $this->getRedirectUri($provider)
    ];

    return $controller->view('Kruzya\Telegram:Auth', 'telegram_login_page', $data);
  }

  protected function handleDirectAuthorization(Controller $controller, ConnectedAccountProvider $provider) {
    $privKey = \XF::app()['session.public']['connectedAccountRequest']['privKey'];
    $url = "tg://resolve?domain={$provider->options['bot_name']}&start={$privKey}";

    return $controller->redirect($url);
  }

  /**
   * For internal usage.
   */
  protected function user($id) {
    $data = \XF::finder('Kruzya\Telegram:User')->where('id', $id)->fetchOne();
    if (!$data) {
      $data = \XF::em()->create('Kruzya\Telegram:User');
      $data->id = $id;
    }

    return $data;
  }
}
