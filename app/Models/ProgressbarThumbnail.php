<?php

namespace App\Models;

class ProgressbarThumbnail extends Model
{
    public function video() {
        return $this->belongsTo(Video::class)->withDefault();
    }

    public function getThumbnailAttribute($value) {
        return asset($value);
    }
}
