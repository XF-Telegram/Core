<?php
namespace Kruzya\Telegram\Objects;

/**
 * This object represents a message.
 */
class Message extends AbstractObject {
  /**
   * Unique message identifier inside this chat.
   *
   * @var integer
   */
  public $message_id;

  /**
   * Optional.
   * Sender, empty for messages sent to channels.
   *
   * @var \Kruzya\Telegram\Objects\User|null
   */
  public $from = null;

  /**
   * Date the message was sent in Unix time
   *
   * @var integer
   */
  public $date;

  /**
   * Conversation the message belongs to
   *
   * @var \Kruzya\Telegram\Objects\Chat
   */
  public $chat;

  /**
   * Optional.
   * For forwarded messages, sender of the original
   * message
   *
   * @var \Kruzya\Telegram\Objects\User|null
   */
  public $forward_from = null;

  /**
   * Optional.
   * For messages forwarded from channels,
   * information about the original channel
   *
   * @var \Kruzya\Telegram\Objects\Chat|null
   */
  public $forward_from_chat = null;

  /**
   * Optional.
   * For messages forwarded from channels,
   * identifier of the original message in the
   * channel
   *
   * @var integer|null
   */
  public $forward_from_message_id = null;

  /**
   * Optional.
   * For messages forwarded from channels,
   * signature of the post author if present
   *
   * @var string|null
   */
  public $forward_signature = null;

  /**
   * Optional.
   * For forwarded messages, date the original
   * message was sent in Unix time
   *
   * @var integer|null
   */
  public $forward_date = null;

  /**
   * Optional.
   * For replies, the original message. Note that
   * the Message object in this field will not
   * contain further reply_to_message fields
   * even if it itself is a reply.
   *
   * @var \Kruzya\Telegram\Objects\Message|null
   */
  public $reply_to_message = null;

  /**
   * Optional.
   * Date the message was last edited in Unix time
   *
   * @var integer|null
   */
  public $edit_date = null;

  /**
   * Optional.
   * The unique identifier of a media message group
   * this message belongs to
   *
   * @var string|null
   */
  public $media_group_id = null;

  /**
   * Optional.
   * Signature of the post author for messages in
   * channels
   *
   * @var string|null
   */
  public $author_signature = null;

  /**
   * Optional.
   * For text messages, the actual UTF-8 text of the
   * message, 0-4096 characters.
   *
   * @var string|null
   */
  public $text = null;

  /**
   * Optional.
   * For text messages, special entities like
   * usernames, URLs, bot commands, etc. that appear
   * in the text
   *
   * @var \Kruzya\Telegram\Objects\MessageEntity[]|null
   */
  public $entities = null;

  /**
   * Optional.
   * For messages with a caption, special entities
   * like usernames, URLs, bot commands, etc. that
   * appear in the caption
   *
   * @var \Kruzya\Telegram\Objects\MessageEntity[]|null
   */
  public $caption_entities = null;

  /**
   * Optional.
   * Message is an audio file, information about the
   * file
   *
   * @var \Kruzya\Telegram\Objects\Audio|null
   */
  public $audio = null;

  /**
   * Optional.
   * Message is a general file, information about the
   * file
   *
   * @var \Kruzya\Telegram\Objects\Document|null
   */
  public $document = null;

  /**
   * Optional.
   * Message is an animation, information about the
   * animation. For backward compatibility, when this
   * field is set, the document field will also be
   * set
   *
   * @var \Kruzya\Telegram\Objects\Animation|null
   */
  public $animation = null;

  /**
   * Optional.
   * Message is a game, information about the game.
   *
   * @var \Kruzya\Telegram\Objects\Game|null
   */
  public $game = null;

  /**
   * Optional.
   * Message is a photo, available sizes of the photo
   *
   * @var \Kruzya\Telegram\Objects\PhotoSize[]|null
   */
  public $photo = null;

  /**
   * Optional.
   * Message is a sticker, information about the
   * sticker
   *
   * @var \Kruzya\Telegram\Objects\Sticker|null
   */
  public $sticker = null;

  /**
   * Optional.
   * Message is a video, information about the video
   *
   * @var \Kruzya\Telegram\Objects\Video|null
   */
  public $video = null;

  /**
   * Optional.
   * Message is a voice message, information about
   * the file
   *
   * @var \Kruzya\Telegram\Objects\Voice|null
   */
  public $voice = null;

  /**
   * Optional.
   * Message is a video note, information about the
   * video message
   *
   * @var \Kruzya\Telegram\Objects\VideoNote|null
   */
  public $video_note = null;

  /**
   * Optional.
   * Caption for the audio, document, photo, video or
   * voice, 0-200 characters
   *
   * @var string|null
   */
  public $caption = null;

  /**
   * Optional.
   * Message is a shared contact, information about
   * the contact
   *
   * @var \Kruzya\Telegram\Objects\Contact|null
   */
  public $contact = null;

  /**
   * Optional.
   * Message is a shared location, information about
   * the location
   *
   * @var \Kruzya\Telegram\Objects\Location|null
   */
  public $location = null;

  /**
   * Optional.
   * Message is a venue, information about the venue
   *
   * @var \Kruzya\Telegram\Objects\Venue|null
   */
  public $venue = null;

  /**
   * Optional.
   * New members that were added to the group or
   * supergroup and information about them
   * (the bot itself may be one of these members)
   *
   * @var \Kruzya\Telegram\Objects\User[]|null
   */
  public $new_chat_members = null;

  /**
   * Optional.
   * A member was removed from the group,
   * information about them
   * (this member may be the bot itself)
   *
   * @var \Kruzya\Telegram\Objects\User|null
   */
  public $left_chat_member = null;

  /**
   * Optional.
   * A chat title was changed to this value
   *
   * @var string|null
   */
  public $new_chat_title = null;

  /**
   * Optional.
   * A chat photo was change to this value
   *
   * @var \Kruzya\Telegram\Objects\PhotoSize[]|null
   */
  public $new_chat_photo = null;

  /**
   * Optional.
   * Service message: the chat photo was deleted
   *
   * @var boolean|null
   */
  public $delete_chat_photo = null;

  /**
   * Optional.
   * Service message: the group has been created
   *
   * @var boolean|null
   */
  public $group_chat_created = null;

  /**
   * Optional.
   * Service message: the supergroup has been
   * created. This field can‘t be received in a
   * message coming through updates, because bot
   * can’t be a member of a supergroup when it is
   * created. It can only be found in
   * reply_to_message if someone replies to a very
   * first message in a directly created supergroup.
   *
   * @var boolean|null
   */
  public $supergroup_chat_created = null;

  /**
   * Optional.
   * Service message: the channel has been created.
   * This field can‘t be received in a message coming
   * through updates, because bot can’t be a member
   * of a channel when it is created. It can only be
   * found in reply_to_message if someone replies to
   * a very first message in a channel.
   *
   * @var boolean|null
   */
  public $channel_chat_created = null;

  /**
   * Optional.
   * The group has been migrated to a supergroup with
   * the specified identifier. This number may be
   * greater than 32 bits and some programming
   * languages may have difficulty/silent defects in
   * interpreting it. But it is smaller than 52 bits,
   * so a signed 64 bit integer or double-precision
   * float type are safe for storing this identifier.
   *
   * @var integer|null
   */
  public $migrate_to_chat_id = null;

  /**
   * Optional.
   * The supergroup has been migrated from a group
   * with the specified identifier. This number may
   * be greater than 32 bits and some programming
   * languages may have difficulty/silent defects in
   * interpreting it. But it is smaller than 52 bits,
   * so a signed 64 bit integer or double-precision
   * float type are safe for storing this identifier.
   *
   * @var integer|null
   */
  public $migrate_from_chat_id = null;

  /**
   * Optional.
   * Specified message was pinned. Note that the
   * Message object in this field will not contain
   * further reply_to_message fields even if it is
   * itself a reply.
   *
   * @var \Kruzya\Telegram\Objects\Message|null
   */
  public $pinned_message = null;

  /**
   * Optional.
   * Message is an invoice for a payment, information
   * about the invoice.
   *
   * @var \Kruzya\Telegram\Objects\Invoice|null
   */
  public $invoice = null;

  /**
   * Optional.
   * Message is a service message about a successful
   * payment, information about the payment.
   *
   * @var \Kruzya\Telegram\Objects\SuccessfulPayment|null
   */
  public $successful_payment = null;

  /**
   * Optional.
   * The domain name of the website on which the user
   * has logged in.
   *
   * @var string|null
   */
  public $connected_website = null;

  /**
   * Optional.
   * Telegram Passport data
   *
   * @var \Kruzya\Telegram\Objects\PassportData|null
   */
  public $passport_data = null;

  protected function getRemappings() {
    return [
      'MessageID'                 => 'message_id',

      'From'                      => 'from',
      'Date'                      => 'date',
      'Chat'                      => 'chat',

      'ForwardFrom'               => 'forward_from',
      'ForwardFromChat'           => 'forward_from_chat',
      'ForwardFromMessageID'      => 'forward_from_message_id',
      'ForwardSignature'          => 'forward_signature',
      'ForwardDate'               => 'forward_date',

      'ReplyToMessage'            => 'reply_to_message',

      'EditDate'                  => 'edit_date',

      'MediaGroupID'              => 'media_group_id',

      'AuthorSignature'           => 'author_signature',

      'Text'                      => 'text',
      'Entities'                  => 'entities',
      'CaptionEntities'           => 'caption_entities',

      'Audio'                     => 'audio',
      'Document'                  => 'document',
      'Animation'                 => 'animation',
      'Game'                      => 'game',
      'Photo'                     => 'photo',
      'Sticker'                   => 'sticker',
      'Video'                     => 'video',
      'Voice'                     => 'voice',
      'VideoNote'                 => 'video_note',
      'Caption'                   => 'caption',
      'Contact'                   => 'contact',
      'Location'                  => 'location',
      'Venue'                     => 'venue',

      'NewChatMembers'            => 'new_chat_members',
      'LeftChatMember'            => 'left_chat_member',
      'NewChatTitle'              => 'new_chat_title',
      'NewChatPhoto'              => 'new_chat_photo',
      'DeleteChatPhoto'           => 'delete_chat_photo',
      'GroupChatCreated'          => 'group_chat_created',
      'SupergroupChatCreated'     => 'supergroup_chat_created',
      'ChannelChatCreated'        => 'channel_chat_created',
      'MigrateToChatID'           => 'migrate_to_chat_id',
      'MigrateFromChatID'         => 'migrate_from_chat_id',

      'PinnedMessage'             => 'pinned_message',

      'Invoice'                   => 'invoice',
      'SuccessfulPayment'         => 'successful_payment',

      'ConnectedWebsite'          => 'connected_website',

      'PassportData'              => 'passport_data',
    ];
  }

  protected function getClassMaps() {
    return [
      'from'                =>  'Kruzya\\Telegram\\Objects\\User',
      'chat'                =>  'Kruzya\\Telegram\\Objects\\Chat',

      'forward_from'        =>  'Kruzya\\Telegram\\Objects\\User',
      'forward_from_chat'   =>  'Kruzya\\Telegram\\Objects\\Chat',

      'reply_to_message'    =>  'Kruzya\\Telegram\\Objects\\Message',

      'entities'            =>  'Kruzya\\Telegram\\Objects\\MessageEntity',
      'caption_entities'    =>  'Kruzya\\Telegram\\Objects\\MessageEntity',

      'audio'               =>  'Kruzya\\Telegram\\Objects\\Audio',
      'document'            =>  'Kruzya\\Telegram\\Objects\\Document',
      'animation'           =>  'Kruzya\\Telegram\\Objects\\Animation',
      'game'                =>  'Kruzya\\Telegram\\Objects\\Game',
      'photo'               =>  'Kruzya\\Telegram\\Objects\\PhotoSize',
      'sticker'             =>  'Kruzya\\Telegram\\Objects\\Sticker',
      'video'               =>  'Kruzya\\Telegram\\Objects\\Video',
      'voice'               =>  'Kruzya\\Telegram\\Objects\\Voice',
      'video_note'          =>  'Kruzya\\Telegram\\Objects\\VideoNote',

      'contact'             =>  'Kruzya\\Telegram\\Objects\\Contact',
      'location'            =>  'Kruzya\\Telegram\\Objects\\Location',
      'venue'               =>  'Kruzya\\Telegram\\Objects\\Venue',

      'new_chat_members'    =>  'Kruzya\\Telegram\\Objects\\User',
      'left_chat_member'    =>  'Kruzya\\Telegram\\Objects\\User',

      'new_chat_photo'      =>  'Kruzya\\Telegram\\Objects\\PhotoSize',

      'pinned_message'      =>  'Kruzya\\Telegram\\Objects\\Message',
      'invoice'             =>  'Kruzya\\Telegram\\Objects\\Invoice',
      'successful_payment'  =>  'Kruzya\\Telegram\\Objects\\SuccessfulPayment',
      'passport_data'       =>  'Kruzya\\Telegram\\Objects\\PassportData',
    ];
  }
}