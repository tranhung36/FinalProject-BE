<?php

namespace App\Http\Controllers\Api\Messages;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Schedule;
use Illuminate\Http\Request;

class GroupMessagesController extends Controller
{
    public function index(Request $request)
    {
        $groupId = $request['group_id'];
        $messageList = Message::where('group_id', $groupId)->orderBy('id', 'DESC')->get();
        return $this->sendResponse($messageList, 'get messages successfully');
    }
    public function checkUserInGroup($userId,$groupId){

    }
}
