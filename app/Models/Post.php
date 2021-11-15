<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';

    protected $fillable = ['slug', 'title', 'content', 'user_id', 'topic_id', 'members'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * A post have one schedule
     */
    public function schedule()
    {
        return $this->hasOne(Schedule::class, 'post_id');
    }
}
