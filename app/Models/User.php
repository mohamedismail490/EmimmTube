<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public $incrementing = false;

    protected static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model -> {$model -> getKeyName()} = (string) Str::uuid();
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['created_since'];
    protected $with    = ['channel'];

    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->format('Y-m-d G:i:s');
    }
    public function getUpdatedAtAttribute($value){
        return Carbon::parse($value)->format('Y-m-d G:i:s');
    }
    public function getCreatedSinceAttribute(){
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    public function channel() {
        return $this->hasOne(Channel::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class)->whereNull('comment_id')->latest('created_at');
    }


    public function toggleVote($entity, $type) {
        $vote = $entity -> votes() -> where('user_id', $this -> id) -> first();
        if ($vote) {
            if ($vote->type === $type) {
                $vote->delete();
            }else {
                $vote -> update([
                    'type' => $type
                ]);
            }
        } else {
            $entity -> votes() -> create([
                'user_id' => $this -> id,
                'type'    => $type
            ]);
        }
        return (object) [
            'status' => true,
            'entity' => $entity->refresh()
        ];
    }

}
