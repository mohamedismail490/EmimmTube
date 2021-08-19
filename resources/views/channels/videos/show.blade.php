@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">{{ $video -> title }}</div>

                    <div class="card-body row justify-content-center">
                        <video-js id="video" data-channelid="{{ $video->channel_id }}"
                                  data-videoid="{{ $video->id }}"
                                  class="video-js vjs-default-skin" controls preload="auto" width="640"
                                  height="360" @if($video->thumbnail) poster="{{asset($video->thumbnail)}} @endif">
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
    <script src="{{ asset('js/player.js') }}"></script>
@endpush
