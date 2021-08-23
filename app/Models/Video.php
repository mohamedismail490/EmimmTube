<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Video extends Model
{
    use HasFactory;

    protected $appends = ['is_up_voted','is_down_voted','up_votes_count','down_votes_count','user_vote'];
    protected $with    = ['channel'];

    public function channel() {
        return $this->belongsTo(Channel::class)->withDefault();
    }

    public function votes() {
        return $this->morphMany(Vote::class, 'voteable');
    }

    public function comments() {
        return $this->hasMany(Comment::class)->whereNull('comment_id');
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



    public function isChannelVideo($channelId){
        return $this->channel_id === $channelId;
    }

    public function editable() {
        return (auth() -> check()) && ($this -> channel -> user_id === auth() -> user() -> id);
    }
}
