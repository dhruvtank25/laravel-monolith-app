<?php

namespace App\Repositories;

use App\Student;
use App\Models\User;
use Auth;
use DB;
use Nahid\Talk\Conversations\Conversation;
use Nahid\Talk\Messages\Message;
use Nahid\Talk\Facades\Talk;

class MessageRepository
{	

	protected $model;
	protected $message;

	function __construct(Conversation $conversation, Message $message)
	{
	    $this->model   = $conversation;
	    $this->message = $message;
	}

	public function createConversation($data_arr)
	{
		$conversation = $this->model->newInstance();
		$conversation->user_one = $data_arr['sender_id'];
		$conversation->user_two = $data_arr['receiver_id'];
		$conversation->subject  = $data_arr['subject'];
		$conversation->status   = 1;
		$result = $conversation->save();
		return $result?$conversation->id:0;
	}

	public function createMessage($data_arr)
	{
		$new_message = $this->message->newInstance();
		$new_message->message 			= $data_arr['message'];
		$new_message->attachments 		= $data_arr['attachments'];
		$new_message->conversation_id 	= $data_arr['conversation_id'];
		$new_message->user_id 			= $data_arr['user_id'];
		$new_message->is_seen 			= 0;
		$result = $new_message->save();
		if($result)
			$new_message->conversation->touch();
		return $result;
	}
	
	public function getParentTeachers($parentEmail)
	{
        // grab all students registered under parents email
        $students = Student::where('email', $parentEmail)->get();

        $teacherMembers = array();

        foreach($students as $student) {
        	$teachers = $student->teachers()->get();

        	foreach($teachers as $teacher) {

        		$user = User::where('email', $teacher->email)->first();
				
				if($user) {
					$teacherMember 			 = array();
					$teacherMember['id']     = $user->id;
					$teacherMember['name']   = $user->name;
					$teacherMember['email']  = $user->email;
					$teacherMember['avatar'] = $user->avatar;
					$teacherMember['role']   = 'Teacher';

					$teacherMembers[$user->id] = $teacherMember;					
				}
        	}
        }

   		return $teacherMembers;
	}

	public function getTeacherParents($teacherId)
	{

		$result = DB::table('lessons_pivot')
					  ->select('student_id')
					  ->where('teacher_id', $teacherId)
					  ->distinct('student_id')
					  ->get();

		$students = array();
		if($result)
		{
			foreach($result as $r)
			{
				$students[] = $r->student_id;
			}
		}


		$parentMembers = array();
		if($students)
		{
			foreach($students as $studentId)
			{
				$student = Student::where('id', $studentId)->first();

				if($student) {
					$parent = User::where('email', $student->email)->first();

					if($parent) {
		        		$parentMember 			= array();
		        		$parentMember['id']     = $parent->id;
		        		$parentMember['name']   = $parent->name;
		        		$parentMember['email']  = $parent->email;
		        		$parentMember['avatar'] = $parent->avatar;
		        		$parentMember['role']   = 'Parent';

		        		$parentMembers[$parent->id] = $parentMember;
					}
				}
			}
		}

		return $parentMembers;
	}

	public function getConversations($user_id, $coach_id='')
	{
		return $this->model
					->where(function($q) use ($user_id) {
						$q->where('user_one', $user_id)
							->orWhere('user_two', $user_id);
					})
					->has('messages')
					->when($coach_id, function($q) use ($coach_id) {
						$q->where(function($query) use ($coach_id) {
							$query->where('user_one', $coach_id)
								->orWhere('user_two', $coach_id);
						});
					})
					->with(['messages' => function($q) {
						$q->with('sender');
					}])
					->orderBy('updated_at', 'desc')
					->paginate(10);
	}

	public function getConversationById($conversation_id)
	{
		return $this->model->find($conversation_id);
	}

	public function getUsers($currentUserId = null,$role = null)
	{
		$result = User::when($role, function($query) use ($role){
                            $query->whereHas("roles", function($q) use ($role){
                                $q->where("name", $role);
                            });
                        })
						->when($currentUserId, function ($query) use ($currentUserId) {
                    		return $query->where('id', '!=',$currentUserId);
                		})
						->get();

		$adminMembers = array();
		foreach($result as $r)
		{
        		$adminMember 		   = array();
        		$adminMember['id']     = $r->id;
        		$adminMember['name']   = $r->first_name.' '.$r->last_name;
        		$adminMember['email']  = $r->email;
        		$adminMember['avatar'] = $r->avatar;
        		$adminMember['role']   = $role;

        		$adminMembers[$r->id] = $adminMember;
		}

		return $adminMembers;
	}

	public function getConversationCount($conversationId)
	{
		$count = DB::table('messages')->where('conversation_id', $conversationId)->count();

		return $count;
	}

	public function getConversationLastMessages($conversationId)
	{
		$result = DB::table('messages')
					  ->where('conversation_id', $conversationId)
					  ->orderBy('id', 'desc')
					  ->limit(5)
					  ->get()
					  ->toArray();

		return array_reverse($result);
	}

	public function checkLoadMore($conversationId, $lastMessageId)
	{
		$count = DB::table('messages')
					  ->where('conversation_id', $conversationId)
					  ->where('id', '<', $lastMessageId)
					  ->count();

		return ($count > 10) ? 1 : 0;
	}

	public function getLoadMoreMessages($conversationId, $lastMessageId)
	{
		$result = DB::table('messages')
					  ->where('conversation_id', $conversationId)
					  ->where('id', '<', $lastMessageId)
					  ->orderBy('id', 'desc')
					  ->limit(10)
					  ->get()
					  ->toArray();

		if($result) {
			foreach($result as &$r)
			{
				$r->created_at = date('d/m/Y h:iA', strtotime($r->created_at));
			}
		}

		return $result;
	}

	public function getConversationUnseenCount($conversationId, $userId)
	{
		$count = DB::table('messages')
					 ->where('conversation_id', $conversationId)
					 ->where('user_id', '!=', $userId)
					 ->where('is_seen', 0)
					 ->count();

		return $count;		
	}

	public function getUserUnseenCount($userId)
	{
		return $this->model
					->where(function($q) use ($userId) {
						$q->where('user_one', $userId)
							->orWhere('user_two', $userId);
					})
					->whereHas('messages', function($query) use ($userId) {
						$query->where('is_seen', 0)->where('user_id', '!=', $userId);
					})
					->count();
	}

	public function makeUnseenMessageSeen($conversationId, $userId)
	{
		return DB::table('messages')
	    	->where('conversation_id', $conversationId)
	    	->where('user_id', '!=', $userId)
	    	->where('is_seen', 0)
	    	->update(['is_seen' => 1]);
	}
	
	public function getMessageAlerts($userId)
	{
		$convResult = DB::table('conversations')
					  ->select('conversations.id')
		              ->where(function ($query) use ($userId) {
		                $query->where('conversations.user_one',  $userId)
		                      ->orWhere('conversations.user_two', $userId);
		              })
					  ->get();

		$conversationIds = array();
		$messageAlerts   = array();
		if($convResult)
		{
			foreach($convResult as $conv)
			{
				$conversationIds[] = $conv->id;
			}

			$messageAlerts = DB::table('messages AS m1')
							  ->select( DB::raw('MAX(m1.id) AS message_id'), 
							  			'm1.user_id',
							  			'm1.conversation_id',
							  			DB::raw('COUNT(*) AS message_count'),
							  			DB::raw('CONCAT(u.first_name, " ", u.last_name) AS user_name'),
							  			'u.avatar AS avatar',
							  			DB::raw('(SELECT message from messages where id = MAX(m1.id)) AS message'),
							  			'm1.created_at'
							  		   )
							  ->join('users AS u', 'u.id', '=', 'm1.user_id')
							  ->where('m1.is_seen', 0)
							  ->where('m1.user_id', '!=', $userId)
							  ->whereIn('m1.conversation_id', $conversationIds)
							  ->groupBy('m1.conversation_id')
							  ->orderBy('message_id', 'DESC')
							  ->get();
		}
		
		return $messageAlerts;
	}
}