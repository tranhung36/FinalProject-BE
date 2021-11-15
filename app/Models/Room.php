<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms';

    protected $fillable = ['wb_id', 'title', 'user_id', 'schedule_id'];

    public function participants()
    {
        return $this->hasMany(User::class, 'user_id', 'id');
    }

    public function room_schedules()
    {
        return $this->hasMany(Schedule::class, 'schedule_id', 'id');
    }

    protected $casts = [
        'schedule_id' => 'array',
        'user_id' => 'array'
    ];
}
