<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Talk;
use Auth;

trait MessageTrait
{

    public function ajaxUnreadMessage()
    {
        $user        = $this->guard()->user();
        $messages    = $this->messageRepo->getMessageAlerts($user->id);
        return response()->json([
                                 'status'   => 'success',
                                 'messages' => $messages
                                ], 200);
    }

    public function ajaxSendMessage(Request $request)
    {
        if ($request->ajax()) {
            Talk::setAuthUserId($this->guard()->user()->id);
            $rules = [
                'message'=>'required',
                '_id'=>'required'
            ];
            $this->validate($request, $rules);
            $body = $request->input('message');
            $userId = $request->input('_id');
            if ($message = Talk::sendMessageByUserId($userId, $body)) {
                return response()->json(['status' => 'success',
                                         'date' => date('d/m/Y h:iA', strtotime($message->created_at)),
                                         'from_user_id' => $message->user_id,
                                         'to_user_id' => $userId,
                                        ], 200);
            }
        }
    }

    public function ajaxMoreMessage(Request $request)
    {
        if ($request->ajax()) {
            Talk::setAuthUserId($this->guard()->user()->id);
            $rules = [
                'user_id'=>'required',
                'last_message_id'=>'required'
            ];
            $this->validate($request, $rules);
            $userId        = $request->input('user_id');
            $lastMessageId = $request->input('last_message_id');
            if ($conversationId = Talk::isConversationExists($userId)) {
                $showLoadMore = $this->messageRepo->checkLoadMore($conversationId, $lastMessageId);
                $messages     = $this->messageRepo->getLoadMoreMessages($conversationId, $lastMessageId);
                return response()->json(['status' => 'success',
                                         'show_load_more' => $showLoadMore,
                                         'messages' => $messages,
                                         'from_user_id' => $this->guard()->user()->id,
                                         'to_user_id' => $userId,
                                        ], 200);
            }
        }
    }

    public function ajaxMakeSeen(Request $request)
    {
        if ($request->ajax()) {
            Talk::setAuthUserId($this->guard()->user()->id);
            $rules = [
                'user_id'=>'required',
            ];
            $this->validate($request, $rules);
            $userId        = $request->input('user_id');
            if ($conversationId = Talk::isConversationExists($userId)) {
                $this->messageRepo->makeUnseenMessageSeen($conversationId, $this->guard()->user()->id);
                return response()->json(['status' => 'success'], 200);
            }
        }
    }

    /**
     * Get the guard to be used.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

}