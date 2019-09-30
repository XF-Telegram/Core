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
            'name'      => '',
            'token'     => '',
            'method'    => 'direct',
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
    
    public function handleAuthorization(Controller $controller, ConnectedAccountProvider $provider, $returnUrl)
    {
        $app = \XF::app();
        $session = $app['session.public'];
        $session->set('connectedAccountRequest', [
            'provider'  => $this->providerId,
            'returnUrl' => $returnUrl,
            'test'      => $this->testMode,
        ]);
        $session->save();

        /** @var \SModders\TelegramCore\SubContainer\Telegram $telegram */
        $telegram = $app['smodders.telegram'];
        $authMethod = $telegram->authMethod($provider->options['method'], $this);

        return $authMethod->setController($controller)
            ->setProviderEntity($provider)
            ->handle();
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
        if (empty($options['token']))
        {
            $options = [];
            return true;
        }
        
        $app = \XF::app();
        
        try {
            /** @var \TelegramBot\Api\BotApi $api */
            $api = $app['smodders.telegram']->api($options['token']);
            $bot = $api->getMe();
            $options['name'] = $bot->getUsername();
        } catch (\Exception $e) {
            $message = $e->getMessage();
            if ($message == 'Not Found')
            {
                $message = \XF::phraseDeferred('smodders_tgcore.invalid_token');
            }

            $error = $message;
            return false;
        }

        return true;
    }

    public function renderConfig(ConnectedAccountProvider $provider)
    {
        $app = \XF::app();
        $authMethods = [];

        /**
         * @var string $authMethodId
         * @var array $data
         */
        foreach ($app['smodders.telegram']['authMethods'] as $authMethodId => $data)
        {
            $authMethods[$authMethodId] = [
                'name'      => \XF::phraseDeferred($data['phrase']),
                'explain'   => \XF::phraseDeferred($data['phrase'] . '_explain'),
            ];
        }

        return $app->templater()->renderTemplate('admin:connected_account_provider_' . $provider->provider_id, [
            'options'       => $this->getEffectiveOptions($provider->options),
            'authMethods'   => $authMethods,
        ]);
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
        
        // Second, prepare secret key.
        $secretKey = hash('sha256', \XF::app()->get('smodders.telegram')->get('bot.token'), true);
        
        // Third, verify hash.
        return hash_equals($data['hash'], hash_hmac('sha256', $hashdata, $secretKey));
    }
}