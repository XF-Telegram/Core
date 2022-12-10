<?php

/**
 * This file is a part of [Telegram] Core.
 * All rights reserved.
 *
 * Developed by SourceModders.
 */

namespace SModders\TelegramCore\ConnectedAccount\Provider;

use SModders\TelegramCore\AuthToken\TelegramToken;
use TelegramBot\Api\Client;
use XF\ConnectedAccount\Provider\AbstractProvider;
use XF\ConnectedAccount\Storage\StorageState;
use XF\Entity\ConnectedAccountProvider;
use XF\Http\Request;
use XF\Mvc\Controller;

class Telegram extends AbstractProvider
{
    public function getOAuthServiceName()
    {
        return 'Telegram';
    }

    public function getDefaultOptions()
    {
        return [
            'bot_id' => -1
        ];
    }

    public function getProviderDataClass()
    {
        return 'SModders\TelegramCore:ProviderData\\' . $this->getOAuthServiceName();
    }
    
    public function getOAuthConfig(ConnectedAccountProvider $provider, $redirectUri = null)
    {
        return [
            'name'      => $provider->options['name'],
            'token'     => $provider->options['token'],
            'method'    => $provider->options['method'],
        ];
    }

    public function isUsable(ConnectedAccountProvider $provider)
    {
        $options = $provider->options;
        if (!isset($options['bot_id']))
        {
            return (!empty($options['token']) && !empty($options['name']));
        }

        return intval($options['bot_id']) != -1;
    }

    public function handleAuthorization(Controller $controller, ConnectedAccountProvider $provider, $returnUrl)
    {
        $app = \XF::app();
        $session = $app['session.public'];
        $session->set('connectedAccountRequest', [
            'provider'  => $this->providerId,
            'smTgCoreBotId' => $provider->options['bot_id'],
            'returnUrl' => $returnUrl,
            'test'      => $this->testMode,
        ]);
        $session->save();

        $bot = $provider->em()->find('SModders\TelegramCore:Bot', $provider->options['bot_id']);
        $viewParams = [
            'botName'       => $bot->username,
            'redirectUri'   => $this->getRedirectUri($provider)
        ];
    
        return $controller->view('SModders\TelegramCore:AuthMethod\OAuth', 'public:smodders_tgcore__auth_page', $viewParams);
    }
    
    public function requestProviderToken(StorageState $storageState, Request $request, &$error = null, $skipStoredToken = false)
    {
        $data = [];
        foreach (['id', 'first_name', 'last_name', 'username', 'photo_url', 'auth_date', 'hash'] as $key)
        {
            $data[$key] = $request->filter($key, 'string', '');
        }
    
        if (!$this->isValidHash($data))
        {
            $error = \XF::phraseDeferred('smodders_tgcore.invalid_hash');
            return false;
        }
        
        $em = \XF::em();

        /** @var \SModders\TelegramCore\Entity\User $user */
        $user = $em->find('SModders\TelegramCore:User', $data['id']);
        if (!$user)
        {
            $user = $em->create('SModders\TelegramCore:User');
        }

        $user->bulkSetIgnore($data);
        $user->updated_at = \XF::$time;
        $user->save();

        $token = new TelegramToken($data['id']);
        $storageState->storeToken($token);
        return $token;
    }
    
    public function verifyConfig(array &$options, &$error = null)
    {
        $options['bot_id'] = intval($options['bot_id']); // just rewrite for storing integer value, not string.

        return true;
    }
    
    protected function isValidHash(array $data)
    {
        // First, prepare all data for hashing.
        $hashdata = [];
        foreach (array_keys($data) as $key)
        {
            if ($key == 'hash' || empty($data[$key]) || is_null($data[$key]))
            {
                continue;
            }
            
            $hashdata[] = "{$key}={$data[$key]}";
        }
        
        sort($hashdata);
        $hashdata = implode("\n", $hashdata);

        // Second, grab a bot and prepare secret key.
        /** @var \XF\Session\Session $session */
        /** @var \SModders\TelegramCore\Entity\Bot $bot */
        $app = \XF::app();
        $session = $app['session.public'];
        $bot = $app->em()->find('SModders\TelegramCore:Bot', $session->get('connectedAccountRequest')['smTgCoreBotId']);

        $secretKey = hash('sha256', $bot->token, true);
        
        // Third, verify hash.
        return hash_equals($data['hash'], hash_hmac('sha256', $hashdata, $secretKey));
    }

    public function renderConfig(ConnectedAccountProvider $provider)
    {
        $app = \XF::app();
        $bots = $app->finder('SModders\TelegramCore:Bot')->order('bot_id')->fetch();

        return $app->templater()->renderTemplate('admin:connected_account_provider_' . $provider->provider_id, [
            'options' => $this->getEffectiveOptions($provider->options),
            'bots' => $bots
        ]);
    }
}