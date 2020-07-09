@extends('layouts.app')

@section('content')
<div class="container">
    @if ($errors->any())
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <h5>Files</h5>
            @forelse($files as $file)
                <div class="row pt-3">
                    <div class="col-md-3">
                        @if($file['type'] === \App\Utils\Utils::MP4TYPE)
                            <video width="100%">
                                <source src="{{ asset('storage/'.$file['path']) }}" type="video/mp4">
                                Your browser does not support HTML video.
                            </video>
                        @elseif($file['type'] === \App\Utils\Utils::PDFTYPE)
                            <img src="{{ asset('/images/pdf.png') }}" class="img-fluid" />
{{--                            <embed src="{{ asset('storage/'.$file['path']) }}" width="100%">--}}
                        @else
                            <img src="{{ asset('storage/'.$file['path']) }}" class="img-fluid" />
                        @endif
                    </div>
                    <div class="col-md-9">
                        <h6 class="pt-1">
                            <strong>
                                <a href="{{ url('storage/'.$file['path']) }}" target="_blank">{{ $file['title'] }}</a>
                            </strong>
                        </h6>
                        <div>
                            Tags:
                            @foreach($file['tags'] as $tag)
                                <span class="badge badge-info">{{ $tag['name'] }}</span>
                            @endforeach
                            <p>
                                <small>{{ $file['created_at'] }}</small>
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <em>No files found.</em>
            @endforelse

        </div>
        <div class="col-md-4">
            <h5>Add new file</h5>
            <form class="mt-2" action="{{ url('/files') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="upload-form-wrapper">
                    <div id="preview-wrapper">
                        <div id="preview"></div>
                        <div id="video-controls">
                            <span class="play-pause-button" onclick="play()"></span>
                        </div>
                    </div>
                    <div class="mt-2">
                        <input type="file" id="file-input" name="file">
                    </div>
                </div>

                <div class="form-group mt-3">
                    <input type="text" class="form-control" placeholder="Title" name="title">
                </div>
                <div class="form-group">
                    <textarea name="description" id="" cols="30" rows="3" class="form-control" placeholder="Description"></textarea>
                </div>
                <div class="form-group">
                    <label for="tags">Tags <small>(Type and hit enter key)</small></label>
                    <input type="text" class="form-control" data-role="tagsinput" name="tags">
                </div>

                <button class="btn btn-block btn-info" type="submit">Save</button>

            </form>
        </div>
    </div>
</div>
@endsection
