@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><h4>Shared with you:</h4></div>

                    <div class="card-body">
                        <div class="row">
                            <div class="row">
                                @foreach($sharedFiles as $file)
                                    <div style="width:30%; border: 2px solid black; border-radius: 7px; padding:7% 0; margin:5px;">
                                        <div class="row">
                                            <div class="text-center col-12 text-info">
                                                @if($file->extension)
                                                    <i class="fa fa-file fa-5x"></i>
                                                @else
                                                    <i class="fa fa-folder fa-5x"></i>
                                                @endif
                                            </div>
                                            <div class="text-center col-12">{{$file->users_name}}{{$file->extension ? '.'.$file->extension : ''}}</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 text-center">
                                                @if(!$file->extension)
                                                    <a href="{{route('folders.open-shared',['folder'=>$file])}}" class="btn btn-outline-info">
                                                        Open folder
                                                    </a>
                                                @else
                                                    <a class="btn btn-outline-info" href="{{route('download-file',['file'=>$file])}}">
                                                        Download file
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
