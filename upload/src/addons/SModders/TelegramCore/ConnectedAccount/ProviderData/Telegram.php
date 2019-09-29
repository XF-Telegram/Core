<?php


namespace SModders\TelegramCore\ConnectedAccount\ProviderData;


use XF\ConnectedAccount\ProviderData\AbstractProviderData;

class Telegram extends AbstractProviderData
{
    /** @var \SModders\TelegramCore\Entity\User|null|false */
    protected $_data = false;
    
    public function getDefaultEndpoint()
    {
        return '';
    }

    public function getProviderKey()
    {
        return $this->storageState->getProviderToken()->getAccessToken();
    }

    public function getProfileUrl()
    {
        $username = $this->get('username');
        if (!$username || empty($username))
        {
            return null;
        }
        
        return "https://t.me/{$username}";
    }
    
    public function getAvatar()
    {
        $username = $this->get('username');
        if (!$username || empty($username))
        {
            return null;
        }
        
        return "https://t.me/i/userpic/320/{$username}.jpg";
    }
    
    public function requestFromEndpoint($key = null, $method = 'GET', $endpoint = null)
    {
        return $this->get($key);
    }
    
    public function get($key)
    {
        if ($this->_data === null)
        {
            return null;
        }

        if ($this->_data === false)
        {
            $this->_data = \XF::em()->find('SModders\TelegramCore:User', $this->getProviderKey());
            if (!$this->_data)
            {
                return null;
            }
    
            $this->_data->updateIfRequired();
        }

        if (!in_array($key, ['id', 'first_name', 'last_name', 'username']))
        {
            return null;
        }

        return $this->_data->get($key);
    }
}