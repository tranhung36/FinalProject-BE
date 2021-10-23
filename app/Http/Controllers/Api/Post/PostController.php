<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
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
            $response = [
                'message' => 'Success',
                'data' => [
                    'posts' => $posts
                ]
            ];
            return response($response, 200);
        } catch (\Exception $e) {
            $response = [
                'message' => 'Error',
                'data' => [
                    'error' => $e
                ]
            ];
            return response($response, 500);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required',
                'user_id' => 'required',
                'topic_id' => 'required'
            ]);
            $request['slug'] = Str::slug($request['title'], '-');
            $post = Post::create($request->all());
            $response = [
                'message' => 'Success',
                'data' => [
                    'post' => $post
                ]
            ];
            return response($response, 200);
        } catch (\Exception $e) {
            $response = [
                'message' => 'Error',
                'data' => [
                    'error' => $e
                ]
            ];
            return response($response, 500);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
            $post = Post::where('slug', $slug)->first();
            $post->update($request->all());
            $response = [
                'message' => 'Success',
                'data' => [
                    'post' => $post
                ]
            ];
            return response($response, 200);
        } catch (\Exception $e) {
            $response = [
                'message' => 'Error',
                'data' => [
                    'error' => $e
                ]
            ];
            return response($response, 500);
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
            $response = [
                'message' => 'Success',
                'data' => [
                    'message' => 'delete successfully'
                ]
            ];
            return response($response, 200);
        } catch (\Exception $e) {
            $response = [
                'message' => 'Error',
                'data' => [
                    'error' => $e
                ]
            ];
            return response($response, 500);
        }

    }
}
