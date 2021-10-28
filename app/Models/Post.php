<?php

namespace App\Models;

<<<<<<< HEAD
use Cviebrock\EloquentSluggable\Sluggable;
=======
>>>>>>> sprint1-post-detail
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

<<<<<<< HEAD
    protected $table = 'posts';
    protected $fillable = [
        'slug',
        'user_id',
        'topic_id',
        'content',
        'title'
    ];
=======
    protected $fillable = ['slug', 'title', 'content', 'user_id', 'topic_id'];

    public function getRouteKeyName()
    {
        return 'slug';
    }
>>>>>>> sprint1-post-detail
}
