<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';

    public function author(){
        return $this->belongsTo(User::class);
    }

    public function article(){
        return $this->belongsTo(Post::class);
    }
}
