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
    public function index()
    {
        try {
            $posts = Post::paginate(5);
            return $this->sendResponse($posts, 'Successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Error', $th->getMessage(), 404);
        }
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
            $post = Post::create([
                'slug' => Str::slug($request['title']),
                'title' => $request['title'],
                'content' => $request['content'],
                'user_id' => auth()->user()->id,
                'topic_id' => (int)$request['topic_id'],
                'members' => (int)$request->members,
            ]);

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
            $newSlug = Str::slug($request['title']);

            if ($slug != $newSlug) {
                $post->update([
                    'title' => $request->title,
                    'slug' => $newSlug,
                    'content' => $request['content'],
                    'user_id' => auth()->user()->id,
                    'topic_id' => (int)$request->topic_id,
                    'members' => (int)$request->members,
                ]);
            } else {
                $post->update([
                    'title' => $request->title,
                    'content' => $request['content'],
                    'user_id' => auth()->user()->id,
                    'topic_id' => (int)$request->topic_id,
                    'members' => (int)$request->members,
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

    public function showPostMember($postId)
    {
        try {
            $post = Post::find($postId);
            if ($post->user_id == auth()->user()->id) {
                // lấy thời khóa biểu của thằng host
                $hostSchedules = Schedule::where([
                    ['post_id', $postId],
                    ['user_id', auth()->user()->id]
                ])->get();

                // lấy thời khóa biểu của mấy thằng member đăng kí vào bài post
                $memberSchedules = Schedule::where([
                    ['post_id', $postId],
                ])->get();

                $membersIds = [];
                foreach ($memberSchedules as $schedule) {
                    $memberId = $schedule->user->id;
                    // kiểm tra thằng member này có trong mảng chưa, nếu có thì thêm
                    if (!in_array($memberId, $membersIds)) {
                        array_push($membersIds, $memberId);
                    }
                }
                $result = [
                    'post_id' => $postId,
                    'owner_id' => auth()->user()->id,
                    'member_ids' => $membersIds,
                    'host_schedules' => $hostSchedules
                ];
                return $this->sendResponse($result, 'get data successfully');
            } else {
                return $this->sendError('Error', 'Access denied', 403);
            }
        } catch (\Throwable $th) {
            return $this->sendError('Error.', $th->getMessage(), 404);
        }
    }

    public function removePostMember(Request $request)
    {
        try {
            $memberIds = $request['memberIds'];
            $postId = $request['postId'];
            $post = Post::find($postId);
            $total = 0;
            if ($post->user_id == auth()->user()->id) {
                $memberIds = json_decode($memberIds, 1);
                foreach ($memberIds as $memberId) {
                    $schedules = Schedule::where([
                        ['post_id', $postId],
                        ['user_id', $memberId]
                    ])->delete();
                    $total += intval($schedules);
                }
                return $this->sendResponse($total, 'remove member successfully');
            } else {
                return $this->sendError('Error', 'Access Denied', 403);
            }
        } catch (\Throwable $th) {
            return $this->sendError('Error.', $th->getMessage());
        }
    }
}
