<?php

namespace App\Http\Controllers\Api\Schedule;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CheckScheduleController extends Controller
{
    public function check(Request $request)
    {
        $user = auth()->user();
        dd($user->schedule_id);
    }
}
