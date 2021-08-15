<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Support\Str;

class Model extends BaseModel
{
    public $incrementing = false;
    protected $guarded = [];
    protected $appends = ['created_since'];

    protected static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model -> {$model -> getKeyName()} = (string) Str::uuid();
        });
    }

    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->format('Y-m-d G:i:s');
    }
    public function getUpdatedAtAttribute($value){
        return Carbon::parse($value)->format('Y-m-d G:i:s');
    }
    public function getCreatedSinceAttribute(){
        return Carbon::parse($this->created_at)->diffForHumans();
    }
}
