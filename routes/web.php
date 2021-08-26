<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::resource('channels', ChannelController::class)->only(['show','update']);
Route::post('channels/{channel}', [ChannelController::class, 'axiosShow']);

Route::group(['prefix' => 'channels', 'middleware' => ['auth']], function () {
    Route::resource('{channel}/subscriptions', SubscriptionController::class)->only(['store','destroy']);
    Route::group(['prefix' => '{channel}/videos'], function () {
        Route::get('upload', [VideoController::class, 'index'])->name('channels.upload');
        Route::post('validate', [VideoController::class, 'validateVideosTypes']);
        Route::post('upload', [VideoController::class, 'store']);
        Route::get('{video}', [VideoController::class, 'show'])->name('channels.videos.show')->withoutMiddleware('auth');
        Route::put('{video}', [VideoController::class, 'updateViews'])->withoutMiddleware('auth');
        Route::put('update/{video}', [VideoController::class, 'update'])->name('channels.videos.update');
    });
});

Route::get('videos/{video}/comments', [CommentController::class, 'index'])->name('video.comments');
Route::get('comments/{comment}/replies', [CommentController::class, 'show'])->name('comment.replies');
Route::get('videos/{video}/progressbar_thumbs', [VideoController::class, 'progressbarThumbs'])->name('video.progressbar.thumbs');
Route::group(['middleware' => ['auth']], function () {
    //Votes
    Route::post('votes/{entityId}/{entityType}/{type}', [VoteController::class, 'vote']);

    Route::post('videos/{video}/comments', [CommentController::class, 'store'])->name('store.video.comment');
});
