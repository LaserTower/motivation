<?php


namespace App\VKProvider;


use App\VKProvider\Input\Callback\MessageNew;
use App\VKProvider\Input\Callback\Typing;
use VK\CallbackApi\VKCallbackApiHandler;

class CallbackApiMyHandler extends VKCallbackApiHandler
{
    protected $core;
    public function __construct($ConversationCore)
    {
        $this->core = $ConversationCore;
    }

    public function messageNew(int $group_id, ?string $secret, array $object)
    {
        \Log::info('receive',['message' => $object['text']]);
        $vkMessage = new MessageNew($object);
        $newEntity = $vkMessage->createEntity();
        if(is_null($newEntity)){
            return;
        }
        $this->core->providerMessage('vk', $newEntity);
    }

    public function messageTypingState(int $group_id, ?string $secret, array $object)
    {
        $vkMessage = new Typing($object);
        $newEntity = $vkMessage->createEntity();
        if(is_null($newEntity)){
            return;
        }
        $this->core->providerMessage('vk', $newEntity);
    }

    public function parseObject(int $group_id, ?string $secret, string $type, array $object) {
        switch ($type) {
            case 'message_typing_state':
                $this->messageTypingState($group_id, $secret, $object);
                break;
            case static::CALLBACK_EVENT_MESSAGE_NEW:
                $this->messageNew($group_id, $secret, $object);
                break;
            case static::CALLBACK_EVENT_MESSAGE_REPLY:
                $this->messageReply($group_id, $secret, $object);
                break;
            case static::CALLBACK_EVENT_MESSAGE_ALLOW:
                $this->messageAllow($group_id, $secret, $object);
                break;
            case static::CALLBACK_EVENT_MESSAGE_DENY:
                $this->messageDeny($group_id, $secret, $object);
                break;
            case static::CALLBACK_EVENT_PHOTO_NEW:
                $this->photoNew($group_id, $secret, $object);
                break;
            case static::CALLBACK_EVENT_PHOTO_COMMENT_NEW:
                $this->photoCommentNew($group_id, $secret, $object);
                break;
            case static::CALLBACK_EVENT_PHOTO_COMMENT_EDIT:
                $this->photoCommentEdit($group_id, $secret, $object);
                break;
            case static::CALLBACK_EVENT_PHOTO_COMMENT_RESTORE:
                $this->photoCommentRestore($group_id, $secret, $object);
                break;
            case static::CALLBACK_EVENT_PHOTO_COMMENT_DELETE:
                $this->photoCommentDelete($group_id, $secret, $object);
                break;
            case static::CALLBACK_EVENT_AUDIO_NEW:
                $this->audioNew($group_id, $secret, $object);
                break;
            case static::CALLBACK_EVENT_VIDEO_NEW:
                $this->videoNew($group_id, $secret, $object);
                break;
            case static::CALLBACK_EVENT_VIDEO_COMMENT_NEW:
                $this->videoCommentNew($group_id, $secret, $object);
                break;
            case static::CALLBACK_EVENT_VIDEO_COMMENT_EDIT:
                $this->videoCommentEdit($group_id, $secret, $object);
                break;
            case static::CALLBACK_EVENT_VIDEO_COMMENT_RESTORE:
                $this->videoCommentRestore($group_id, $secret, $object);
                break;
            case static::CALLBACK_EVENT_VIDEO_COMMENT_DELETE:
                $this->videoCommentDelete($group_id, $secret, $object);
                break;
            case static::CALLBACK_EVENT_WALL_POST_NEW:
                $this->wallPostNew($group_id, $secret, $object);
                break;
            case static::CALLBACK_EVENT_WALL_REPOST:
                $this->wallRepost($group_id, $secret, $object);
                break;
            case static::CALLBACK_EVENT_WALL_REPLY_NEW:
                $this->wallReplyNew($group_id, $secret, $object);
                break;
            case static::CALLBACK_EVENT_WALL_REPLY_EDIT:
                $this->wallReplyEdit($group_id, $secret, $object);
                break;
            case static::CALLBACK_EVENT_WALL_REPLY_RESTORE:
                $this->wallReplyRestore($group_id, $secret, $object);
                break;
            case static::CALLBACK_EVENT_WALL_REPLY_DELETE:
                $this->wallReplyDelete($group_id, $secret, $object);
                break;
            case static::CALLBACK_EVENT_BOARD_POST_NEW:
                $this->boardPostNew($group_id, $secret, $object);
                break;
            case static::CALLBACK_EVENT_BOARD_POST_EDIT:
                $this->boardPostEdit($group_id, $secret, $object);
                break;
            case static::CALLBACK_EVENT_BOARD_POST_RESTORE:
                $this->boardPostRestore($group_id, $secret, $object);
                break;
            case static::CALLBACK_EVENT_BOARD_POST_DELETE:
                $this->boardPostDelete($group_id, $secret, $object);
                break;
            case static::CALLBACK_EVENT_MARKET_COMMENT_NEW:
                $this->marketCommentNew($group_id, $secret, $object);
                break;
            case static::CALLBACK_EVENT_MARKET_COMMENT_EDIT:
                $this->marketCommentEdit($group_id, $secret, $object);
                break;
            case static::CALLBACK_EVENT_MARKET_COMMENT_RESTORE:
                $this->marketCommentRestore($group_id, $secret, $object);
                break;
            case static::CALLBACK_EVENT_MARKET_COMMENT_DELETE:
                $this->marketCommentDelete($group_id, $secret, $object);
                break;
            case static::CALLBACK_EVENT_GROUP_LEAVE:
                $this->groupLeave($group_id, $secret, $object);
                break;
            case static::CALLBACK_EVENT_GROUP_JOIN:
                $this->groupJoin($group_id, $secret, $object);
                break;
            case static::CALLBACK_EVENT_GROUP_CHANGE_SETTINGS:
                $this->groupChangeSettings($group_id, $secret, $object);
                break;
            case static::CALLBACK_EVENT_GROUP_CHANGE_PHOTO:
                $this->groupChangePhoto($group_id, $secret, $object);
                break;
            case static::CALLBACK_EVENT_GROUP_OFFICERS_EDIT:
                $this->groupOfficersEdit($group_id, $secret, $object);
                break;
            case static::CALLBACK_EVENT_POLL_VOTE_NEW:
                $this->pollVoteNew($group_id, $secret, $object);
                break;
            case static::CALLBACK_EVENT_USER_BLOCK:
                $this->userBlock($group_id, $secret, $object);
                break;
            case static::CALLBACK_EVENT_USER_UNBLOCK:
                $this->userUnblock($group_id, $secret, $object);
                break;
        }
    }
}