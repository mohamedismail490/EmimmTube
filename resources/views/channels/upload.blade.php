@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <channel-uploads :channel="{{ $channel }}" inline-template>
                <div class="col-md-8">
                    <div class="card p-3 d-flex justify-content-center align-items-center" v-if="!selected">
                        <svg class="video-upload" onclick="document.getElementById('video-files').click()" width="70px" height="70px"
                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 461 461">
                            <path
                                d="M365.26 67.4H95.74A95.74 95.74 0 0 0 0 163.13v134.73a95.74 95.74 0 0 0 95.74 95.74h269.52A95.74 95.74 0 0 0 461 297.87V163.14a95.74 95.74 0 0 0-95.74-95.75zM300.5 237.05l-126.06 60.12a5.06 5.06 0 0 1-7.24-4.57v-124a5.06 5.06 0 0 1 7.34-4.52l126.06 63.88a5.06 5.06 0 0 1-.1 9.09z"
                                fill="#f61c0d"/>
                        </svg>
                        <p class="text-center">Upload Videos</p>
                        <input type="file" id="video-files" ref="videos" multiple style="display: none;"
                               @change="upload">
                    </div>
                    <div class="card p-3" v-else>
                        <div class="my-4">
                            <div class="progress mb-3">
                                <div class="progress-bar progress-bar-striped progress-bar-animated " role="progressbar"
                                     style="width: 50%"
                                     aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="d-flex justify-content-center align-items-center"
                                         style="height: 180px; color: white; font-size: 18px; background: #808080;">
                                        Loading thumbnail ...
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <h4 class="text-center">
                                        My Awesome Video
                                    </h4>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </channel-uploads>
        </div>
    </div>
@endsection
