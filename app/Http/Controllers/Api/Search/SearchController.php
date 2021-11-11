<?php

namespace App\Http\Controllers\Api\Search;

use App\Http\Controllers\Controller;
use App\Models\Post;

class SearchController extends Controller
{
    public function searchPost($post)
    {
        $post_info = Post::where('title', 'like', "%{$post}%")
            ->orWhere('content', 'like', "%{$post}%")->get();
        if (!$post_info->isEmpty()) {
            return $this->sendResponse($post_info, 'Successfully.');
        }
        return $this->sendError('Error.', 'Post not found.', 404);
    }
}
