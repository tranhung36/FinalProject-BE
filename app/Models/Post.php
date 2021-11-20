<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';

    protected $fillable = [
        'slug',
        'title',
        'content',
        'user_id',
        'topic_id',
        'members',
        'number_of_lessons',
        'number_of_weeks'
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected $casts = [
        'registered_members' => 'array',
    ];

    /**
     * A post have many schedule
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'id', 'user_id');
    }
}
