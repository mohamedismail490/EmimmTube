@extends('layouts.app')

@push('styles')
    <link href="https://vjs.zencdn.net/7.14.3/video-js.css" rel="stylesheet"/>
    <style>
        .video-js .vjs-control-bar {
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
        }
        .vjs-default-skin .vjs-big-play-button {
            line-height: 1.5em;
            height: 1.5em;
            width: 3em;
            /* Align center */
            left: 50%;
            top: 50%;
            margin-left: -1.5em;
            margin-top: -0.75em;
        }
    </style>

    <!-- Video.js base CSS -->
    <!-- add classes (video-js vjs-theme-city) to video-js -->
    <!-- <link href="https://unpkg.com/video.js@7/dist/video-js.min.css" rel="stylesheet"/> -->
    <!-- <link href="https://unpkg.com/@videojs/themes@1/dist/city/index.css" rel="stylesheet"/> -->
@endpush
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ $video -> title }}</div>

                    <div class="card-body row justify-content-center">
                        <video-js id="video" class="video-js vjs-default-skin" controls preload="auto" width="640"
                                  height="360" poster="{{asset($video->thumbnail)}}"
                                  data-setup='{ "aspectRatio":"640:360", "playbackRates": [1, 1.5, 2] }'
                        >
                            <source src='{{ asset(Storage::url("videos/{$video->id}/{$video->id}.m3u8")) }}'
                                    type="application/x-mpegURL">
                        </video-js>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://vjs.zencdn.net/7.14.3/video.min.js"></script>
    <script>
        videojs('video');
    </script>
@endpush
