<?php

namespace App\Http\Controllers\Api\Topic;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\StoreTopicRequest;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $topics = Topic::paginate(5);
            return $this->success($topics);
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTopicRequest $request)
    {
        try {
            $request['slug'] = Str::slug($request['name'], '-');
            $topic = Topic::create($request->all());
            return $this->success($topic);
        } catch (\Exception $e) {
            return $this->error($e);
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
            $topic = Topic::where('slug', $slug)->first();
            return $this->success($topic);
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
            $topic = Topic::where('slug', $slug)->first();
            $topic->update($request->all());
            return $this->success($topic);
        } catch (\Exception $e) {
            return $this->error($e);
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
            $topic = Topic::where('slug', $slug)->first();
            $topic->delete();
            $data='delete successfully';
            return $this->success($data);
        } catch (\Exception $e) {
            return $this->error($e);
        }
    }
}
