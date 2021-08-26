<?php

namespace App\Http\Controllers;

use App\Http\Requests\Videos\CreateCommentRequest;
use App\Models\Comment;
use App\Models\Video;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Video $video) {
        return $video->comments()->paginate(10);
    }

    public function show(Request $request, Comment $comment) {
//        $firstTenReplied = $comment->replies()->paginate(10);
//        if (($request->page) ){
//            $skippedRepliesArr = [];
//            if (count($firstTenReplied) > 0) {
//                foreach ($firstTenReplied as $item){
//                    $skippedRepliesArr[] = $item->id;
//                }
//            }
//            $replies = $comment->replies()
//                ->whereNotIn('id', $skippedRepliesArr)
//                ->paginate(50);
//        }else{
//            $replies = $firstTenReplied;
//        }
//        return $replies;
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
