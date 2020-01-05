@extends('layouts.master')

@section('content')
<div class="container">

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                @if($id != -1)
                <p>Processed chunk {{$id}}</p>
                @endif
                <div class="card-body">
                    @foreach($proposalchunks as $key=>$chunk)
                    @if($key > $id)
                    <p>
                        <a href="{{action('JobsController@dochunk', $key)}}">Chunk {{$key}} of
                            {{count($proposalchunks)}}</a>
                    </p>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
