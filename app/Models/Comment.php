<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    public function user() {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function video() {
        return $this->belongsTo(Video::class)->withDefault();
    }

    public function replies() {
        return $this->hasMany(self::class, 'comment_id', 'id');
    }

    public function votes() {
        return $this->morphMany(Vote::class, 'voteable');
    }
}
