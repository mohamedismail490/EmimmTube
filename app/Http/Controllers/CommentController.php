<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Video;

class CommentController extends Controller
{
    public function index(Video $video) {
        return $video->comments()->latest('created_at')->paginate(10);
    }

    public function show(Comment $comment) {
        return $comment->replies()->latest('created_at')->paginate(10);
    }
}
