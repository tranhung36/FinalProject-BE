<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoomController extends Controller
{
    protected $wb_id;
    public function __construct()
    {
        $this->wb_id = (string) Str::uuid();
    }

    public function create_room(Request $request, $slug)
    {
        $post = Post::where('slug', $slug)->first();
        $user = $request->user();
        $participants = User::select('id', 'email')->whereIn('id', $post->registered_members)->get();
        $room = Room::create([
            'title' => $request['title'],
            'wb_id' => $this->wb_id,
            'user_id' => $user->id,
            'participants' => $participants,
        ]);
        return $this->sendResponse($room, 'Successfully');
    }
}
