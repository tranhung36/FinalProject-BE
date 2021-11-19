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
            return $this->sendError($e, 'get comments failed');
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'post_id' => 'required',
                'content' => 'required'
            ]);
            $comment = Comment::create([
                'user_id' => auth()->user()->id,
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
                'post_id' => 'required',
                'content' => 'required'
            ]);
            $comment = Comment::where([
                ['user_id' => auth()->user()->id],
                ['id' => $id]
            ]);
            if ($comment) {
                $comment->update([
                    'user_id' => auth()->user()->id,
                    'post_id' => $request['post_id'],
                    'content' => $request['content']
                ]);
                return $this->sendResponse($comment, 'update comment successfully');
            } else {
                return $this->sendError([], 'fail to create comments', 400);
            }


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
