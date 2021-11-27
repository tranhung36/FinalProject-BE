<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $q = $request->input('q');
        if ($q) {
            $posts = Post::where("title", "like", "%{$q}%")
                ->orWhere("content", "like", "%{$q}%")->orderBy('created_at', 'DESC')->paginate(5);
        } else {
            $all_posts = Post::orderBy('created_at', 'DESC')->paginate(5);
            return $this->sendResponse($all_posts, 'Successfully');
        }
        if ($posts->isEmpty()) {
            return $this->sendError('Error', 'Post not found', 404);
        }
        $posts->appends(array('q' => $q));
        return $this->sendResponse($posts, 'Successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {
        try {
            if ($request->validator->fails()) {
                return $this->sendError('Validation error.', $request->validator->messages(), 403);
            }

            $user = $request->user();

            $post = Post::create([
                'slug' => Str::slug($request['title']),
                'title' => $request['title'],
                'content' => $request['content'],
                'user_id' => $user->id,
                'topic_id' => (int)$request['topic_id'],
                'members' => (int)$request->members,
                'number_of_lessons' => $request['number_of_lessons'],
                'number_of_weeks' => $request['number_of_weeks'],
            ]);
            $post->registered_members = Schedule::select('user_id')->where('post_id', $post->id)->distinct()->get();
            $post->schedules = Schedule::where('post_id', $post->id)->get();
            return $this->sendResponse($post, 'Post created successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Error.', $th->getMessage(), 404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        try {
            $post = Post::where('slug', $slug)->first();
            $post->load('schedules');
            $post->registered_members = Schedule::select('user_id')->where('post_id', $post->id)->distinct()->get();
            return $this->sendResponse($post, 'Post retrieved successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Post not found.', $th->getMessage(), 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, $slug)
    {
        try {
            if ($request->validator->fails()) {
                return $this->sendError('Validation error.', $request->validator->messages(), 403);
            }
            $post = Post::where('slug', $slug)->first();
            $user = $request->user();
            $newSlug = Str::slug($request['title']);

            if ($slug != $newSlug) {
                $post->update([
                    'title' => $request->title,
                    'slug' => $newSlug,
                    'content' => $request['content'],
                    'user_id' => $user->id,
                    'topic_id' => (int)$request->topic_id,
                    'members' => (int)$request->members,
                    'number_of_lessons' => $request['number_of_lessons'],
                    'number_of_weeks' => $request['number_of_weeks'],
                ]);
            } else {
                $post->update([
                    'title' => $request->title,
                    'content' => $request['content'],
                    'user_id' => $user->id,
                    'topic_id' => (int)$request->topic_id,
                    'members' => (int)$request->members,
                    'number_of_lessons' => $request['number_of_lessons'],
                    'number_of_weeks' => $request['number_of_weeks'],
                ]);
            }

            return $this->sendResponse($post, 'Post updated successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Error.', $th->getMessage(), 404);
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
            if (auth()->user()->id == $post->user_id) {
                $post->delete();
                return $this->sendResponse($post, 'Post deleted successfully');
            }
            return $this->sendError('Error', 'Unauthorized', 401);
        } catch (\Throwable $th) {
            return $this->sendError('Error.', $th->getMessage(), 404);
        }
    }
}
