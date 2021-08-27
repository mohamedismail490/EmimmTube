@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-center" style="font-size: large;"> Welcome to EmimmTube</div>
                </div>
            </div>
            <homepage-recent-videos></homepage-recent-videos>
            <homepage-videos></homepage-videos>
        </div>
    </div>
@endsection
