<?php

namespace App\Http\Controllers\Coach;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\MessageRepository;
use Nahid\Talk\Messages\MessageRepository as TalkMessageRepo;
use App\Models\Role;
use Talk;
use Auth;
use App\Helpers\FileUploadHelper;

class MessageController extends Controller
{
    
    function __construct(MessageRepository $messageRepo, TalkMessageRepo $talkMessageRepo)
    {
        $this->messageRepo     = $messageRepo;
        $this->talkMessageRepo = $talkMessageRepo;
    }

    public function index()
    {
        $page_title = 'Messages';
        $user_id = Auth::guard('coach')->id();
        $threads = $this->messageRepo->getConversations($user_id);
        return view('coach.messages', compact('page_title', 'threads', 'user_id'));
    }

    public function storeConversation(Request $request)
    {
        $inputs   = $request->all();
        $coach_id = Auth::guard('coach')->id();
        $admin_id = Role::where('name', 'admin')->first()->id;
        if(!isset($inputs['conv_id']) || $inputs['conv_id']==0) {
            $subject  = $request->subject;
            $data_arr = array('sender_id' => $coach_id, 'receiver_id' => $admin_id, 'subject' => $subject);
            $conv_id  = $this->messageRepo->createConversation($data_arr);
        } else  {
            $conv_id = $inputs['conv_id'];
            $conversation = $this->messageRepo->getConversationById($conv_id);
            if(!$conversation || ($conversation->user_one!=$coach_id && $conversation->user_two!=$coach_id))
                abort(401);
        }
        if($conv_id!=0) {
            // Save new message
            Talk::setAuthUserId($coach_id);

            //$file_name = FileUploadHelper::uploadDoc($file, 'attachment');

            $message_arr = array(
                            'message' => $inputs['message'], 
                            'attachments' => $inputs['attachments'], 
                            'conversation_id' => $conv_id, 
                            'user_id' => $coach_id
                        );
            $result = $this->messageRepo->createMessage($message_arr);
            if ($result)
                return redirect()->back()->withSuccess('Message Sent!');
            else
                return redirect()->back()->withErrors('Failed to send message');
        }
        else
            return redirect()->back()->withErrors('Something went wrong');
    }

    public function uploadAttachments(Request $request)
    {
        $inputs = $request->all();
        $validation_arr = array('file' => 'required|mimes:pdf,jpg,jpeg,png');
        $validatedData = $request->validate($validation_arr);

        $type   = 'attachment';
        $user = Auth::guard('coach')->user();
        $inputs['first_name'] = $user->first_name;
        $inputs['last_name']  = $user->last_name;
        $file_name  = FileUploadHelper::uploadDoc($request->file, $type);
        $file_url   = FileUploadHelper::getDocPath($file_name, $type);
        return response()->json([
                                'success'   => 'true', 
                                'file_name' => $file_name,
                                'file_url'  => $file_url, 
                                'message'   => 'File uploaded successfully'
                            ], 200);
    }

    public function getUnreadThreads()
    {
        $admin_id  = Auth::guard('coach')->id();
        return $this->messageRepo->getUserUnseenCount($admin_id);
    }

    public function markThreadSeen($thread_id)
    {
        $coach_id     = Auth::guard('coach')->id();
        $conversation = $this->messageRepo->getConversationById($thread_id);
        if(!$conversation || ($conversation->user_one!=$coach_id && $conversation->user_two!=$coach_id))
            return response()->json(['success' => 'false', 'message' => 'Unauthorized request'], 200);
        $updated_count = $this->messageRepo->makeUnseenMessageSeen($thread_id, $coach_id);
        if($updated_count)
            return response()->json(['success' => 'true', 'message' => 'Conversation marked read']);
        else
            return response()->json(['success' => 'false', 'message' => 'No conversation to mark read']);
    }

}