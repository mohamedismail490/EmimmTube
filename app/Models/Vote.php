<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vote extends Model
{
    use HasFactory;

    public function voteable() {
        return $this->morphTo();
    }
}
