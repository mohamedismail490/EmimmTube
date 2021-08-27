<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('homepage');
    }

    public function recentVideos() {
        $yesterday = Carbon::yesterday()->format('Y-m-d');
        return Video::live()
            ->whereDate('created_at', '>', $yesterday)
            ->latest('created_at')
            ->paginate(8);
    }
    public function videos() {
        return Video::live()
            ->latest('views')
            ->paginate(16);
    }
}
