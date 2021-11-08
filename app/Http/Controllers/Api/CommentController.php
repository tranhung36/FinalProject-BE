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
            }])->where('post_id', $postId)->get();
            return $this->sendResponse($comments, 'get comments successfully');
        } catch (\Exception $e) {
            return $this->sendError($e, 'get comments failed');
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required',
                'post_id' => 'required',
                'content' => 'required'
            ]);
            $comment = Comment::create([
                'user_id' => $request['user_id'],
                'post_id' => $request['post_id'],
                'content' => $request['content']
            ]);
            return $this->sendResponse($comment, 'create comment successfully');
        } catch (\Exception $e) {
            return $this->sendError($e, 'fail to create comment');
        }

    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'user_id' => 'required',
                'post_id' => 'required',
                'content' => 'required'
            ]);
            $comment = Comment::find($id);
            $comment->update([
                'user_id' => $request['user_id'],
                'post_id' => $request['post_id'],
                'content' => $request['content']
            ]);
            return $this->sendResponse($comment, 'update comment successfully');
        } catch (\Exception $e) {
            return $this->sendError($e, 'fail to create comment');
        }

    }

    public function destroy($id)
    {
        try {
            $comment = Comment::find($id);
            $result = $comment->delete();
            return $this->sendResponse($result, 'delete comment successfully');
        } catch (\Exception $e) {
            return $this->sendError($e, 'fail to delete comment');
        }
    }
}
