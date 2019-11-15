@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if (\Session::has('success'))
                    <div class="alert alert-success">
                        <p>@php echo html_entity_decode(\Session::get('success'), ENT_HTML5) @endphp</p>
                    </div><br/>
                @endif
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                 <div class="card" style="margin-top:20px;">
                    @if (\Session::has('error'))
                        <div class="alert alert-danger">
                            <p>@php echo html_entity_decode(\Session::get('error'), ENT_HTML5) @endphp</p>
                        </div><br/>
                    @endif
                    <div class="card-header">List of emails</div>

                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th colspan="2">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($persons_email as $email)
                                <tr>
                                    <td>{{$email['first_name']}}</td>
                                    <td>{{$email['last_name']}}</td>
                                    <td>{{$email['email']}}</td>
                                    <td><a href="{{action('Admin\EmailController@edit', $email['id'])}}" class="btn btn-warning">Edit</a></td>
                                    <td>
                                        <form action="{{action('Admin\EmailController@destroy', $email['id'])}}" method="post">
                                            @csrf
                                            <input name="_method" type="hidden" value="DELETE">
                                            <button class="btn btn-danger" type="submit">Delete</button>
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
