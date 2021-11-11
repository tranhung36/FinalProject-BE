<?php

namespace App\Http\Controllers\Api\Schedule;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScheduleRequest;
use App\Models\Post;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Schedule::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreScheduleRequest $request)
    {
        try {
            if ($request->validator->fails()) {
                return $this->sendError('Validation error.', $request->validator->messages(), 403);
            }

            $schedule = Schedule::create([
                'post_id' => (int) $request['post_id'],
                'day_id' => $request['day_id'],
                'time_id' => $request['time_id'],
                'user_id' => auth()->user()->id,
                'value' => $request['value']
            ]);
            return $this->sendResponse($schedule, 'Successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Error.', $th->getMessage(), 404);
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
            $post = Post::where('slug', $slug)->first();
            $schedules = Schedule::where('post_id', $post->id)->get();
            $schedules->load('user_profile');
            return $this->sendResponse($schedules, 'Successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Error.', $th->getMessage(), 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $schedule = Schedule::where('id', $id)->first();
            if (auth()->user()->id == $schedule->user_id) {
                $schedule->delete();
                return $this->sendResponse($schedule, 'Successfully.');
            }
            return $this->sendError('Error', $th->getMessage(), 401);
        } catch (\Throwable $th) {
            return $this->sendError('Error.', $th->getMessage(), 404);
        }
    }
}
