<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function getCommentsByPost($postId)
    {
        try {
            $comments = Comment::with(['user' => function ($query) {
                $query->select('*');
            }])->where('post_id', $postId)->paginate(10);
            return $this->sendResponse($comments, 'get comments successfully');
        } catch (\Exception $e) {
            return $this->sendError($e, 'get comments failed', 403);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'post_id' => 'required',
                'content' => 'required'
            ]);
            $user = $request->user();
            $comment = Comment::create([
                'user_id' => $user->id,
                'post_id' => $request['post_id'],
                'content' => $request['content']
            ]);
            return $this->sendResponse($comment, 'create comment successfully');
        } catch (\Exception $e) {
            return $this->sendError($e, 'fail to create comment', 403);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'post_id' => 'required',
                'content' => 'required'
            ]);
            $user = $request->user();
            $comment = Comment::find($id);
            $comment->update([
                'user_id' => $user->id,
                'post_id' => $request['post_id'],
                'content' => $request['content']
            ]);
            return $this->sendResponse($comment, 'update comment successfully');
        } catch (\Exception $e) {
            return $this->sendError($e, 'fail to create comment', 403);
        }
    }

    public function destroy($id)
    {
        $comment = Comment::find($id);
        if ($comment->user_id == auth()->user()->id) {
            $result = $comment->delete();
            return $this->sendResponse($result, 'delete comment successfully');
        }
        return $this->sendError('error', 'unauthorised', 401);
    }
}
