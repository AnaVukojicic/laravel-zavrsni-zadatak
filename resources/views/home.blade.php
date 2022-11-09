@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h4>Welcome, {{auth()->user()->name}}!</h4><h5>Keep track of your storage</h5></div>

                <div class="card-body">
                    <div class="row">
                        <div class="row mb-1">
                            <div class="col-10"><h4>Shared with you:</h4></div>
                            <div class="col-2"><a href="{{route('shared-files')}}" class="float-end btn btn-primary">See all</a></div>
                        </div>
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
                    <div class="row mt-3 mb-2">
                        <h4>Used storage:</h4>
                    </div>
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated"
                             role="progressbar" style="width:{{auth()->user()->used_storage_percentage}}%">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            {{number_format($usedStorage,3)}}MB used of 1GB
                        </div>
                    </div>
                    <div class="row mt-5">
                        <h4>Uploaded files in %:</h4>
                    </div>
                    <div class="row">
                        <div class="col-6 offset-3">
                            <canvas id="myChart" width="400" height="400"></canvas>
                        </div>
                        <div class="col-12 h5 d-none text-danger" id="no-files-container">
                            You haven't uploaded any files!
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('myChart').getContext('2d');
        let backgroundColors=[];
        let numberOfFiles=[];
        let extensionNames=[];
        let extensions=@json($extensions);
        let total={{$totalFiles}};
        let others=0;
        let othersArray=[];
        let othersArrayNumbers=[];
        let othersSum=0;
        extensions.forEach(e=>{
            if((e.number_of_files*100/total)>5) {
                backgroundColors.push(`rgb(${Math.floor(Math.random() * 255)},${Math.floor(Math.random() * 255)},${Math.floor(Math.random() * 255)})`);
                numberOfFiles.push(e.number_of_files*100/total);
                extensionNames.push(e.name);
            }else{
                if(e.number_of_files>0){
                    othersArray.push(e);
                    othersArrayNumbers.push(e.number_of_files*100/total);
                    others++;
                    othersSum+=e.number_of_files*100/total;
                }
            }
        });
        if(others>0){
            while(othersSum>5){
                let restored=Math.max(...othersArrayNumbers);
                let e=othersArray[othersArrayNumbers.indexOf(restored)];
                othersArrayNumbers.splice(othersArrayNumbers.indexOf(e),1);
                othersArray.splice(othersArrayNumbers.indexOf(restored),1);
                backgroundColors.push(`rgb(${Math.floor(Math.random() * 255)},${Math.floor(Math.random() * 255)},${Math.floor(Math.random() * 255)})`);
                numberOfFiles.push(restored);
                extensionNames.push(e.name);
                others--;
                othersSum-=restored;
            }
            backgroundColors.push(`rgb(${Math.floor(Math.random() * 255)},${Math.floor(Math.random() * 255)},${Math.floor(Math.random() * 255)})`);
            numberOfFiles.push(others*100/total);
            extensionNames.push('Others');
        }
        if(extensionNames.length==0){
            document.getElementById('no-files-container').classList.remove('d-none');
            document.getElementById('myChart').classList.add('d-none');
        }
        const data = {
            labels: extensionNames,
            datasets: [{
                label:"",
                data: numberOfFiles,
                backgroundColor: backgroundColors,
                hoverOffset: 4
            }]
        };
        const config = {
            type: 'pie',
            data: data,
            options: {}
        };
        const myChart = new Chart(
            document.getElementById('myChart'),
            config
        );
    </script>
@endsection
