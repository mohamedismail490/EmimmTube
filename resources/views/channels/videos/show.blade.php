@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card video-card" style="width: 92% !important; border: none !important; margin-left: auto !important; margin-right: auto !important;">
                    @if($video->editable())
                        <form action="{{ route('channels.videos.update', ['channel' => $video->channel->id, 'video' => $video->id]) }}" method="POST">
                            @csrf
                            @method('PUT')
                    @endif
                            <div class="card-body" style="margin: -20px !important;">
                                <video-js id="video" data-channelid="{{ $video->channel_id }}"
                                          data-videoid="{{ $video->id }}"
                                          class="video-js vjs-default-skin" controls preload="auto" width="640"
                                          height="360" @if($video->thumbnail) poster="{{asset($video->thumbnail)}} @endif">
                                    <source src='{{ asset(Storage::url("videos/{$video->id}/{$video->id}.m3u8")) }}'
                                            type="application/x-mpegURL">
                                </video-js>

                                <div class="d-flex row justify-content-between align-items-center">
                                    <div class="col-md-12">
                                        <h5 class="mt-3">
                                            {{ $video -> title }}
                                        </h5>
                                    </div>
                                    <div class="col-md-8 views-info">
                                        {{$video -> views}} {{ \Illuminate\Support\Str::plural('view', $video->views) }}
                                    </div>
                                    <div class="col-md-4">
                                        <div class="float-right">
                                            <votes :initial_entity="{{ $video }}"></votes>
                                        </div>

                                    </div>
                                </div>

                                <hr>

                                <div class="d-flex row justify-content-between align-items-center mt-2">
                                    <div class="col-md-7">
                                        <div class="media">
                                            <a href="{{ route('channels.show', $video -> channel -> id) }}" style="text-decoration: none; color: #fff;">
                                                <img class="rounded-circle mr-3" src="{{ $video -> channel -> image() }}" width="50"
                                                     height="50" alt="..."></a>
                                            <div class="media-body ml-2">
                                                <h5 class="mt-0 mb-0">
                                                    <a href="{{ route('channels.show', $video -> channel -> id) }}" style="text-decoration: none; color: #fff; font-size: small; font-weight: bold;">{{$video -> channel -> name}}</a>
                                                </h5>
                                                <span
                                                    class="small release-info">Published on {{ $video -> formatted_created_at }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="float-right">
                                            <subscribe-button :initial-channel="{{ $video -> channel }}"></subscribe-button>
                                        </div>
                                    </div>
                                </div>
                                @if($video->editable())
                                    <hr>
                                    <div>
                                        <input type="text" class="form-control" name="title" id="title" placeholder="Enter Video Title" value="{{ $video -> title }}">
                                        @error('title')
                                        <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div class="mt-2">
                                        <textarea class="form-control" name="description" id="description" cols="3" rows="4" placeholder="Enter Video Description">{!! $video -> description !!}</textarea>
                                        @error('description')
                                        <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-info btn-sm mt-3">Update Video Details</button>
                                    </div>
                                @else
                                    @if($video -> description != null)
                                        <div class="mt-3" style="margin-left: 75px; max-width: 550px; font-size: small;">
                                            {!! $video -> description !!}
                                        </div>
                                    @endif
                                @endif
                            </div>
                    @if($video->editable())
                        </form>
                    @endif
                </div>

                <div class="card mt-5 p-5 video-card" style="width: 92% !important; border: none !important; margin-left: auto !important; margin-right: auto !important;">
                    <div class="form-inline my-4 w-full">
                        <input type="text" class="form-control form-control-sm w-80">
                        <button class="btn btn-sm btn-primary">
                            <small>Add comment</small>
                        </button>
                    </div>

                    <div class="card my-3 video-card" style="width: 92% !important; border: none !important; margin-left: auto !important; margin-right: auto !important;">
                        <img width="30" height="30" class="rounded-circle mr-3" src="https://picsum.photos/id/42/200/200" alt="">

                        <div class="media-body">
                            <h6 class="mt-0">
                                Username
                            </h6>
                            <small>
                                Comment Body
                            </small>

                            <div class="d-flex">
                                <votes :initial_entity="{{$video}}"></votes>
                                <button class="btn btn-sm ml-2 btn-default">
                                    Add Reply
                                </button>
                            </div>

                            <div class="form-inline my-4 w-full">
                                <input type="text" class="form-control form-control-sm w-80">
                                <button class="btn btn-sm btn-primary">
                                    <small>Add reply</small>
                                </button>
                            </div>

                            <div>
                                <div class="media my-3">
                                    <a class="mr-3" href="#">
                                        <img width="30" height="30" class="rounded-circle mr-3" src="https://picsum.photos/id/42/200/200" alt="">
                                    </a>
                                    <div class="media-body">
                                        <h6 class="mt-0">Reply Username</h6>
                                        <small >
                                            Reply Body
                                        </small>

                                        <votes :initial_entity="{{$video}}"></votes>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button class="btn btn-info btn-sm">Load Replies</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button class="btn btn-success">
                            Load More
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('js/player.js') }}"></script>
@endpush
