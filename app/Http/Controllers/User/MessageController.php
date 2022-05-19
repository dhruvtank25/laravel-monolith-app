<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\MessageRepository;
use App\Traits\MessageTrait;
use Talk;
use Auth;

class MessageController extends Controller
{
    use MessageTrait;

	public $messageRepo;

    public function __construct(MessageRepository $messageRepo) {

    	$this->messageRepo = $messageRepo;
    }

    /**
     * Get the guard to be used.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('user');
    }

    public function index(Request $request)
    {
        // grab current user data
        $user = Auth::guard('user')->user();
        
        $admin 			= $this->messageRepo->getUsers(null, 'admin');
        $coaches        = $this->messageRepo->getUsers(null, 'coach');
        $members 		= $admin + $coaches;

        Talk::setAuthUserId($user->id);

        if(count($members))
        {
        	foreach($members as &$member)
        	{
        		$member['conversations'] = array();
				if ($conversationId = Talk::isConversationExists($member['id'])) {
				    $member['conversation_id'] = $conversationId;
				    $member['message_count'] = $this->messageRepo->getConversationCount($conversationId);
				    $member['messages'] = $this->messageRepo->getConversationLastMessages($conversationId);
				    $member['unseen_count'] = $this->messageRepo->getConversationUnseenCount($conversationId, $user->id);
				}        		
        	}
        }

        return view('user.messages.messages', compact('user', 'members'));
    }

}
