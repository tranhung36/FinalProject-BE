<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Resources\PostResource;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $posts = Post::paginate(5);
            return $this->sendResponse($posts, 'Successfully.');
        } catch (\Throwable $th) {
            return $this->sendError($th, 'Error', 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'slug' => 'required',
                'title' => 'required',
                'content' => 'required'
            ]);

            $post = Post::create([
                'slug' => Str::slug($request->title),
                'title' => $request->title,
                'content' => $request->content,
                'user_id' => auth()->user()->id
            ]);

            return $this->sendResponse($post, 'Post created successfully.');
        } catch (\Throwable $th) {
            return $this->sendError($th, 'Validation error.', 403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        try {
            $post = new PostResource(Post::where('slug', $slug)->first());
            return $this->sendResponse($post, 'Post retrieved successfully.');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), 'Post not found.', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        try {
            $post = Post::where('slug', $slug)->first();
            $post->update($request->all());
            return $this->sendResponse($post, 'Post updated successfully.');
        } catch (\Exception $e) {
            return $this->sendError($e, 'Invalid validation', 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        try {
            $post = Post::where('slug', $slug)->first();
            $post->delete();
            return $this->sendResponse($post, 'Post deleted successfully.');
        } catch (\Exception $e) {
            return $this->sendError($e, 'Error', 403);
        }
    }
}
