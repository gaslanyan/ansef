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
            <div class="col-md-10 offset-md-2">
                 <div class="card" >
                    <div class="card-header">Dashboard</div>

                    <div class="card-body" style="overflow:auto;">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Journal</th>
                                <th>Year</th>
                                <th>Domestic</th>
                                <th>Ansef</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($publications as $publication)
                                <tr>
                                    <td>{{$publication['id']}}</td>
                                    <td>{{$publication['title']}}</td>
                                    <td>{{$publication['journal']}}</td>
                                    <td>{{$publication['year']}}</td>
                                    <td>{{$publication['domestic']}}</td>
                                    <td>{{$publication['ansef_supported']}}</td>
                                    <td><a href="{{action('Applicant\PublicationsController@edit', $publication['id'])}}" class="btn btn-warning">Edit</a></td>
                                    <td>
                                        <form action="{{action('Applicant\PublicationsController@destroy', $publication['id'])}}" method="post">
                                            @csrf
                                            <input name="_method" type="hidden" value="DELETE">
                                            <button class="btn btn-danger" type="submit">Delete Publications</button>
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
