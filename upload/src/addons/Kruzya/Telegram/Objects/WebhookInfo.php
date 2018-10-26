<?php
namespace Kruzya\Telegram\Objects;

/**
 * Contains information about the current status of a webhook.
 */
class WebhookInfo extends AbstractObject {
  /**
   * Webhook URL, may be empty if webhook is not set
   * up
   *
   * @var string
   */
  public $url;

  /**
   * True, if a custom certificate was provided for
   * webhook certificate checks
   *
   * @var boolean
   */
  public $has_custom_certificate;

  /**
   * Number of updates awaiting delivery
   *
   * @var integer
   */
  public $pending_update_count;

  /**
   * Optional.
   * Unix time for the most recent error that
   * happened when trying to deliver an update via
   * webhook
   *
   * @var integer|null
   */
  public $last_error_date = null;

  /**
   * Optional.
   * Error message in human-readable format for the
   * most recent error that happened when trying to
   * deliver an update via webhook
   *
   * @var string|null
   */
  public $last_error_message = null;

  /**
   * Optional.
   * Maximum allowed number of simultaneous HTTPS
   * connections to the webhook for update delivery
   *
   * @var integer|null
   */
  public $max_connections = null;

  /**
   * Optional.
   * A list of update types the bot is subscribed to.
   * Defaults to all update types
   *
   * @var integer|null
   */
  public $allowed_updates = null;

  protected function getRemappings() {
    return [
      'URL'                   => 'url',
      'HasCustomCertificate'  => 'has_custom_certificate',

      'PendingUpdateCount'    => 'pending_update_count',
      'LastErrorDate'         => 'last_error_date',
      'LastErrorMessage'      => 'last_error_message',

      'MaxConnections'        => 'max_connections',
      'AllowedUpdates'        => 'allowed_updates',
    ];
  }

  protected function getClassMaps() {
    return [];
  }
}