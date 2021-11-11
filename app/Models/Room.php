<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms';

    protected $fillable = ['uuid', 'title', 'user_id'];

    public function participants()
    {
        return $this->hasMany(User::class, 'user_id', 'id');
    }
}
