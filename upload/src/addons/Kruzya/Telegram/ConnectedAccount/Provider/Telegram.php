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

use Kruzya\Telegram\Hash;

class Telegram extends AbstractProvider {
  public function getOAuthServiceName() {
    return 'Telegram';
  }

  public function getProviderDataClass() {
    return 'Kruzya\Telegram:ProviderData\\' . $this->getOAuthServiceName();
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
    $session->set('connectedAccountRequest', [
      'provider'  => $this->providerId,
      'returnUrl' => $returnUrl,
      'test'      => $this->testMode,
    ]);
    $session->save();

    $auth_method = $app->options()->telegramAuthMethod;
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

    if (!$this->isValidAuth($id, $data, $auth_date, $hash, $error))
      return false;

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
  protected function isValidAuth($id, array $data, $auth_date, $hash, &$error = null) {
    if (!Hash::isCorrectHash($id, $data, $auth_date, $hash)) {
      $error = \XF::phraseDeferred('error_occurred_while_connecting_with_x', ['provider' => $this->getTitle()]);
      return false;
    }

    return true;
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
    $url = "tg://resolve?domain={$provider->options['bot_name']}&start=auth";

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
