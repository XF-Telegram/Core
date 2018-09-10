<?php
namespace Kruzya\Telegram\Objects;

/**
 * This object represents a chat.
 */
class Chat extends AbstractObject {
  /**
   * Unique identifier for this chat.
   * This number may be greater than 32 bits and some
   * programming languages may have difficulty/silent
   * defects in interpreting it. But it is smaller
   * than 52 bits, so a signed 64 bit integer or
   * double-precision float type are safe for storing
   * this identifier.
   *
   * @var integer
   */
  public $id;

  /**
   * Type of chat, can be either "private", "group",
   * "supergroup" or "channel".
   *
   * @var string
   */
  public $type;

  /**
   * Optional.
   * Title, for supergroups, channels and group chats
   *
   * @var string|null
   */
  public $title = null;

  /**
   * Optional.
   * Username, for private chats, supergroups and
   * channels if available
   *
   * @var string|null
   */
  public $username = null;

  /**
   * Optional.
   * First name of the other party in a private
   * chat
   *
   * @var string|null
   */
  public $first_name = null;

  /**
   * Optional.
   * Last name of the other party in a private
   * chat
   *
   * @var string|null
   */
  public $last_name = null;

  /**
   * Optional.
   * True if a group has 'All Members Are Admins'
   * enabled.
   *
   * @var boolean|null
   */
  public $all_members_are_administrators = null;

  /**
   * Optional.
   * Chat photo. Returned only in getChat.
   *
   * @var \Kruzya\Telegram\Objects\ChatPhoto|null
   */
  public $photo = null;

  /**
   * Optional.
   * Description, for supergroups and channel chats.
   * Returned only in getChat.
   *
   * @var string|null
   */
  public $description = null;

  /**
   * Optional.
   * Chat invite link, for supergroups and channel
   * chats. Returned only in getChat.
   *
   * @var string|null
   */
  public $invite_link = null;

  /**
   * Optional.
   * Pinned message, for supergroups and channel
   * chats. Returned only in getChat.
   *
   * @var \Kruzya\Telegram\Objects\Message|null
   */
  public $pinned_message = null;

  /**
   * Optional.
   * For supergroups, name of group sticker set.
   * Returned only in getChat.
   *
   * @var string|null
   */
  public $sticker_set_name = null;

  /**
   * Optional.
   * True, if the bot can change the group sticker
   * set. Returned only in getChat.
   *
   * @var boolean|null
   */
  public $can_set_sticker_set = null;

  protected function getRemappings() {
    return [
      'ID'                          => 'id',
      'Type'                        => 'type',

      'Title'                       => 'title',

      'Username'                    => 'username',
      'FirstName'                   => 'first_name',
      'LastName'                    => 'last_name',

      'AllMembersAreAdministrators' => 'all_members_are_administrators',
      'Photo'                       => 'photo',
      'Description'                 => 'description',
      'InviteLink'                  => 'invite_link',
      'PinnedMessage'               => 'pinned_message',

      'StickerSetName'              => 'sticker_set_name',
      'CanSetStickerSet'            => 'can_set_sticker_set',
    ];
  }

  protected function getClassMaps() {
    return [
      'photo'           => 'Kruzya\\Telegram\\Objects\\ChatPhoto',
      'pinned_message'  => 'Kruzya\\Telegram\\Objects\\PinnedMessage',
    ];
  }
}