<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $with = ['user'];
    protected $appends = ['created_since','formatted_created_at','replies_count','is_owner','is_up_voted','is_down_voted','up_votes_count','down_votes_count','user_vote'];

    public function user() {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function video() {
        return $this->belongsTo(Video::class)->withDefault();
    }

    public function replies() {
        return $this->hasMany(self::class, 'comment_id', 'id')->whereNotNull('comment_id')->latest('created_at');
    }

    public function votes() {
        return $this->morphMany(Vote::class, 'voteable');
    }

    public function getRepliesCountAttribute() {
        return $this->replies()->count();
    }

    public function getIsOwnerAttribute() {
        return auth()->check() ? ($this->user_id === auth()->user()->id) : false;
    }
    public function getIsUpVotedAttribute() {
        if (auth()->check()) {
            $upVote = $this->votes()
                ->where('user_id', auth()->user()->id)
                ->where('type','up')
                ->first();
            if (!empty($upVote)){
                return true;
            }
        }
        return false;
    }
    public function getIsDownVotedAttribute() {
        if (auth()->check()) {
            $downVote = $this->votes()
                ->where('user_id', auth()->user()->id)
                ->where('type','down')
                ->first();
            if (!empty($downVote)){
                return true;
            }
        }
        return false;
    }
    public function getUpVotesCountAttribute(){
        return $this->votes()->where('type', 'up')->count();
    }
    public function getDownVotesCountAttribute(){
        return $this->votes()->where('type', 'down')->count();
    }
    public function getUserVoteAttribute() {
        if (auth()->check()) {
            $userVote = $this->votes()->where('user_id', auth()->user()->id)->first();
            if (!empty($userVote)){
                return (object) $userVote;
            }
        }
        return false;
    }
}
