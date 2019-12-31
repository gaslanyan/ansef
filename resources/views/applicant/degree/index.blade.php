@extends('layouts.master')

@section('content')
    <div class="container">
        @if (\Session::has('success'))
            <div class="alert alert-success">
                <p>@php echo html_entity_decode(\Session::get('success'), ENT_HTML5) @endphp</p>
            </div><br />
        @elseif (\Session::has('wrong'))
            <div class="alert alert-success">
                <p>{{ \Session::get('wrong') }}</p>
            </div><br/>
        @endif
        <div class="row justify-content-center">
            <div class="col-md-12">
                 <div class="card" >
                    <div class="card-header">Dashboard</div>

                    <div class="card-body" style="overflow:auto;">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Description</th>
                                <th>Year</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($degrees as $degree)
                                <tr>
                                    <td>{{$degree['id']}}</td>
                                    <td>{{$degree['degree_id']}}</td>
                                    <td>{{$degree['year']}}</td>
                                   <td><a href="{{action('Base\DegreeController@edit', $degree['id'])}}" class="btn btn-warning">Edit</a></td>
                                    <td>
                                        <form action="{{action('Base\DegreeController@destroy', $degree['id'])}}" method="post">
                                            @csrf
                                            <input name="_method" type="hidden" value="DELETE">
                                            <button class="btn btn-danger" type="submit">Delete Degree</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
