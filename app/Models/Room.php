<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['wb_id', 'title', 'user_id', 'participants'];

    public function participants()
    {
        return $this->hasMany(User::class);
    }

    protected $casts = [
        'participants' => 'array'
    ];
}
