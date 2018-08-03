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
    $session = \XF::app()['session.public'];
    $session->set('connectedAccountRequest', [
      'provider'  => $this->providerId,
      'returnUrl' => $returnUrl,
      'test'      => $this->testMode
    ]);
    $session->save();

    $data = [
      'bot_name'     => $provider->options['bot_name'],
      'redirect_uri' => $this->getRedirectUri($provider)
    ];

    return $controller->view('Telegram:Auth', 'telegram_login_page', $data);
  }

  public function requestProviderToken(StorageState $storageState, Request $request, &$error = null, $skipStoredToken = false) {
    $id         = $request->get('id');
    $first_name = $request->get('first_name');
    $last_name  = $request->get('last_name');
    $username   = $request->get('username');
    $photo_url  = $request->get('photo_url');
    $auth_date  = $request->get('auth_date');

    $hash       = $request->get('hash');

    $data = [];
    $data['id'] = $id;
    $data['first_name'] = $first_name;

    if ($last_name !== false)
      $data['last_name'] = $last_name;
    if ($username !== false)
      $data['username'] = $username;
    if ($photo_url !== false)
      $data['photo_url'] = $photo_url;
    $data['auth_date'] = $auth_date;

    $hashvar = [];
    foreach ($data as $key => $value)
      $hashvar[] = "{$key}={$value}";
    sort($hashvar);
    $data_check_string = implode("\n", $hashvar);

    $secret_key  = hash('sha256', $storageState->getProvider()->options['bot_token'], true);
    $except_hash = hash_hmac('sha256', $data_check_string, $secret_key);

    if (!hash_equals($hash, $except_hash)) {
      $error = \XF::phraseDeferred('error_occurred_while_connecting_with_x', ['provider' => $this->getTitle()]);
      return false;
    }

    $token = new TelegramToken($id);

    $data = \XF::finder('Kruzya\\Telegram:User')->where('id', $id)->fetchOne();
    if (!$data) {
      $data = \XF::em()->create('Kruzya\\Telegram:User');
      $data->id = $id;
    }

    $data->first_name = $first_name;
    $data->last_name  = $last_name;
    $data->username   = $username;
    $data->photo_url  = $photo_url;
    $data->updated    = time();
    $data->save();

    $storageState->storeToken($token);
    return $token;
  }
}
