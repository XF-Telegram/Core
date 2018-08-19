<?php
namespace Kruzya\Telegram;

use GuzzleHttp\Exception\RequestException;

class API {
  /**
   * @var \GuzzleHttp\Client $HTTPClient
   */
  private $HTTPClient;

  /**
   * @var array
   */
  private $globalData = [];

  public function __construct($token, $proxy = NULL) {
    /**
     * prepare options for HTTP Client creating.
     */
    $BaseURL = "https://api.telegram.org/bot{$token}/";
    $options = [
      // in XF we use Guzzle with old version.
      // if developers update him, we should be ready.
      // for we pass `base_uri` (for new 6+ versions) and `base_url` (for oldest)
      'base_uri'  => $BaseURL,
      'base_url'  => $BaseURL,

      'timeout'   => 2.0,
    ];

    if ($proxy !== NULL) {
      $options['proxy'] = $proxy;
    }

    /**
     * create client and save to global class instance scope.
     */
    $this->HTTPClient = \XF::app()->http()->createClient($options);
  }

  public function setGlobalVariables($variables) {
    $this->globalData = $variables;
    return $this;
  }
  /**
   * magic handler for all requests.
   */
  private function _execute($method, array $options = [], array $files = []) {
    /**
     * prepare a request options.
     */
    $request = [];
    $options = array_merge($this->globalData, $options);
    foreach ($options as $option => $value)
      if ($value === NULL)
        unset($options[$option]);

    if (count($options) > 0) {
      $request['json'] = $options;
    }
    if (count($files) > 0) {
      $request['multipart'] = $files;
    }

    /**
     * send
     */
    $body = [];
    $client = $this->HTTPClient;
    try {
      $body = $client
        ->post($method, $request)
        ->getBody();
    } catch (RequestException $e) {
      \XF::logException($e);

      $body = $e->getResponse();

      if ($body)
        $body = $body->getBody();
      else 
        $body = json_encode([
          'ok'  => false,
        ]);
    } catch (\Exception $e) {
      \XF::logException($e);

      $body = json_encode([
        'ok'  => false,
      ]);
    }

    return json_decode($body, true);
  }

  public function __call($method, array $arguments) {
    return $this->_execute($method, $arguments[0]);
  }
}