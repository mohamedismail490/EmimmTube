<?php

namespace App\Http\Controllers;

use App\Http\Requests\Videos\CreateCommentRequest;
use App\Models\Comment;
use App\Models\Video;

class CommentController extends Controller
{
    public function index(Video $video) {
        return $video->comments()->paginate(10);
    }

    public function show(Comment $comment) {
        return $comment->replies()->paginate(10);
    }

    public function store(CreateCommentRequest $request, Video $video) {
        return $video->comments()->create([
            'body'       => $request->body,
            'user_id'    => $request->user()->id,
            'comment_id' => $request->comment_id
        ])->fresh();
    }
}
