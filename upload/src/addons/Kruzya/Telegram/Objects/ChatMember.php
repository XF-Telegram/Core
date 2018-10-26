<?php
namespace Kruzya\Telegram\Objects;

/**
 * This object contains information about one member
 * of a chat.
 */
class ChatMember extends AbstractObject {
  /**
   * Information about the user
   *
   * @var \Kruzya\Telegram\Objects\User
   */
  public $user;

  /**
   * The member"s status in the chat. Can be:
   * -> creator
   * -> administrator
   * -> member
   * -> restricted
   * -> left
   * -> kicked
   */
  public $status;

  /**
   * Optional.
   * Restricted and kicked only. Date when
   * restrictions will be lifted for this user, unix
   * time
   *
   * @var integer|null
   */
  public $until_date;

  /**
   * Optional.
   * Administrators only. True, if the bot is allowed
   * to edit administrator privileges of that user
   *
   * @var boolean|null
   */
  public $can_be_edited = null;

  /**
   * Optional.
   * Administrators only. True, if the administrator
   * can change the chat title, photo and other
   * settings
   *
   * @var boolean|null
   */
  public $can_change_info = null;

  /**
   * Optional.
   * Administrators only. True, if the administrator
   * can post in the channel, channels only
   *
   * @var boolean|null
   */
  public $can_post_messages = null;

  /**
   * Optional.
   * Administrators only. True, if the administrator
   * can edit messages of other users and can pin
   * messages, channels only
   *
   * @var boolean|null
   */
  public $can_edit_messages = null;

  /**
   * Optional.
   * Administrators only. True, if the administrator
   * can delete messages of other users
   *
   * @var boolean|null
   */
  public $can_delete_messages = null;

  /**
   * Optional.
   * Administrators only. True, if the administrator
   * can invite new users to the chat
   *
   * @var boolean|null
   */
  public $can_invite_users = null;

  /**
   * Optional.
   * Administrators only. True, if the administrator
   * can restrict, ban or unban chat members
   *
   * @var boolean|null
   */
  public $can_restrict_members = null;

  /**
   * Optional.
   * Administrators only. True, if the administrator
   * can pin messages, supergroups only
   *
   * @var boolean|null
   */
  public $can_pin_messages = null;

  /**
   * Optional.
   * Administrators only. True, if the administrator
   * can add new administrators with a subset of his
   * own privileges or demote administrators that he
   * has promoted, directly or indirectly (promoted
   * by administrators that were appointed by the
   * user)
   *
   * @var boolean|null
   */
  public $can_promote_members = null;

  /**
   * Optional.
   * Restricted only. True, if the user can send text
   * messages, contacts, locations and venues
   *
   * @var boolean|null
   */
  public $can_send_messages = null;

  /**
   * Optional.
   * Restricted only. True, if the user can send
   * audios, documents, photos, videos, video notes
   * and voice notes, implies can_send_messages
   *
   * @var boolean|null
   */
  public $can_send_media_messages = null;

  /**
   * Optional.
   * Restricted only. True, if the user can send
   * animations, games, stickers and use inline bots,
   * implies can_send_media_messages
   *
   * @var boolean|null
   */
  public $can_send_other_messages = null;

  /**
   * Optional.
   * Restricted only. True, if user may add web page
   * previews to his messages, implies
   * can_send_media_messages
   *
   * @var boolean|null
   */
  public $can_add_web_page_previews = null;

  protected function getRemappings() {
    return [
      'User'                  =>  'user',
      'Status'                =>  'status',
      'UntilDate'             =>  'until_date',
      'CanBeEdited'           =>  'can_be_edited',

      'CanChangeInfo'         =>  'can_change_info',
      'CanPostMessages'       =>  'can_post_messages',
      'CanEditMessages'       =>  'can_edit_messages',
      'CanDeleteMessages'     =>  'can_delete_messages',
      'CanInviteUsers'        =>  'can_invite_users',
      'CanRestrictMembers'    =>  'can_restrict_members',
      'CanPinMessages'        =>  'can_pin_messages',
      'CanPromoteMembers'     =>  'can_promote_members',

      'CanSendMessages'       =>  'can_send_messages',
      'CanSendMediaMessages'  =>  'can_send_media_messages',
      'CanSendOtherMessages'  =>  'can_send_other_messages',
      'CanAddWebPagePreviews' =>  'can_add_web_page_previews',
    ];
  }

  protected function getClassMaps() {
    return [
      'user'                  => 'Kruzya\\Telegram\\Objects\\User',
    ];
  }
}