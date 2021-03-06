@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Persons Information</div>
                <div class="card-body" style="overflow:auto;">
                    @include('partials.status_bar')

                    @if(!empty($persons))
                    <table class="table table-responsive-md table-sm table-bordered display" id="example">
                        <thead>
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Birth Date</th>
                                <th>Birth Place</th>
                                <th>Sex</th>
                                <th>Nationality</th>
                                <th colspan="2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($persons as $person)
                            <tr>
                                <td>{{$person['first_name']}}</td>
                                <td>{{$person['last_name']}}</td>
                                <td>{{$person['birthdate']}}</td>
                                <td>{{$person['birthplace']}}</td>
                                <td>{{$person['sex']}}</td>
                                <td>{{$person['nationality']}}</td>
                                <td>
                                    <a href="{{action('Referee\PersonController@edit', $person['id'])}}"
                                        title="full_edit" class="full_edit"><i class="fa fa-edit"></i>
                                    </a>
                                    <a href="{{action('Referee\PersonController@show', $person['id'])}}" class="view"
                                        title="View"><i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{action('Referee\PersonController@changePassword')}}">
                                        <i class="fa fa-lock"></i>
                                    </a>

                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p>Can't find data</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
