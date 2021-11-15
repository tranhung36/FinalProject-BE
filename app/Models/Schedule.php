<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedules';

    protected $fillable = ['user_id', 'day_id', 'post_id', 'time_id', 'value'];

    /**
     * Schedule belong to post
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
