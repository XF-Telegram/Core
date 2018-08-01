<?php
namespace Kruzya\Telegram\ConnectedAccount\ProviderData;

use \XF\ConnectedAccount\ProviderData\AbstractProviderData;
use XF\ConnectedAccount\Storage\StorageState;

class Telegram extends AbstractProviderData {
  private $_data = null;

  public function __construct($providerId, StorageState $storageState) {
    parent::__construct($providerId, $storageState);
  }

  public function getDefaultEndpoint() {
    return '';
  }

  public function getProviderKey() {
    return $this->storageState->getProviderToken()->getAccessToken();
  }

  public function getProfileUrl() {
    $username = $this->getUsername();
    if (!$username || empty($username))
      return NULL;

    return 'https://t.me/' . $username;
  }

  public function getFirstName()  { return $this->getTelegramData('first_name'); }
  public function getLastName()   { return $this->getTelegramData('last_name');  }
  public function getUsername()   { return $this->getTelegramData('username');   }
  public function getAvatar()     { return $this->getTelegramData('photo_url');  }

  private function getTelegramData($key) {
    $id = $this->getProviderKey();

    if ($this->_data === false)
      return NULL;

    if ($this->_data === NULL) {
      $this->_data = \XF::finder('Kruzya\\Telegram:User')->where('id', $id)->fetchOne();

      if (!$this->_data) {
        $this->_data = false;
        return NULL;
      }
    }

    switch ($key) {
      case 'first_name':  return $this->_data->first_name;
      case 'last_name':   return $this->_data->last_name;
      case 'photo_url':   return $this->_data->photo_url;
      case 'username':    return $this->_data->username;

      default:            return NULL;
    }
  }

  public function requestFromEndpoint($key = null, $method = 'GET', $endpoint = null) {
    return $this->getTelegramData($key);
  }
}

