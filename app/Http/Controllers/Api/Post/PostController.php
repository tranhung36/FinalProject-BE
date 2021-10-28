<?php

namespace App\Http\Controllers\Api\Post;

<<<<<<< HEAD
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
=======
use App\Http\Resources\PostResource;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
>>>>>>> sprint1-post-detail
use Illuminate\Support\Str;

class PostController extends Controller
{
<<<<<<< HEAD
=======

>>>>>>> sprint1-post-detail
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
<<<<<<< HEAD
        try {
            $posts = Post::paginate(5);
            return $this->success($posts);
        } catch (\Exception $e) {
            return $this->error($e);
        }
    }

    public function show($slug)
    {
        try {
            $post = Post::where('slug', $slug)->first();
            return $this->success($post);
        } catch (\Exception $e) {
            return $this->error($e);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

=======
        //
>>>>>>> sprint1-post-detail
    }

    /**
     * Store a newly created resource in storage.
     *
<<<<<<< HEAD
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {
        try {
            $request['slug'] = Str::slug($request['title'], '-');
            $post = Post::create($request->all());
            return $this->success($post);
        } catch (\Exception $e) {
            return $this->error($e);
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
=======
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
>>>>>>> sprint1-post-detail
    }

    /**
     * Update the specified resource in storage.
     *
<<<<<<< HEAD
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        try {
            $post = Post::where('slug', $slug)->first();
            $post->update($request->all());
            return $this->success($post);
        } catch (\Exception $e) {
            return $this->error($e);
        }

=======
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
>>>>>>> sprint1-post-detail
    }

    /**
     * Remove the specified resource from storage.
     *
<<<<<<< HEAD
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        try {
            $post = Post::where('slug', $slug)->first();
            $post->delete();
            $data='delete successfully';
            return $this->success($data);
        } catch (\Exception $e) {
            return $this->error($e);
        }

=======
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
>>>>>>> sprint1-post-detail
    }
}
