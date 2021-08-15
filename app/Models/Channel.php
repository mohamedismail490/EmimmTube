<?php

namespace App\Models;

class Channel extends Model
{
    public function user() {
        return $this->belongsTo(User::class);
    }
}
