@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                <div class="card">
                    <div class="card-header">Send emails
                        <br>
                        <i class="fa fa-info text-blue all"> * {{Lang::get('messages.required_all')}}</i>
                    </div>

                    <div class="card-body card_body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (\Session::has('success'))
                            <div class="alert alert-success">
                                <p>@php echo html_entity_decode(\Session::get('success'), ENT_HTML5) @endphp</p>
                            </div><br/>
                        @endif
                        @if (\Session::has('error'))
                            <div class="alert alert-danger">
                                <p>@php echo html_entity_decode(\Session::get('error'), ENT_HTML5) @endphp</p>
                            </div>
                        @endif
                        <form method="post" action="{{ action('Admin\InvitationController@store') }}">
                            @csrf
                            <div class="form-group col-lg-12">
                                <div class="row">
                                    <div class="form-group col-lg-12 emails">
                                        <label for="template">Templates *:</label>
                                        <select id="template" class="form-control" name="template">
                                            <option>Select template</option>
                                            @if(!empty($messages))
                                                @foreach($messages as $key => $message)
                                                    <option value="{{$message->id}}" @if(old('template') == $message->id) {{'selected'}} @endif>{{$message->text}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <table class="table table-responsive-md table-sm table-bordered display"
                                               id="example"
                                               style="width:100%">
                                            <thead>
                                            <tr>
                                                <th></th>
                                                <th>#</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th>Name</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($users as $user)
                                                <tr>
                                                    <td></td>
                                                    <td>
                                                        <label for="email{{$user->id}}" class="label">
                                                            <input type="checkbox" class="form-control" name="email[]"
                                                                   value="{{$user->email}}"
                                                                   id="email{{$user->id}}">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </td>
                                                    <td>{{$user->email}}</td>
                                                    <td>{{$user->type}}</td>
                                                    <td>{{$user->last_name}}, {{$user->first_name}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-lg-12">
                                <button type="submit" class="btn btn-primary">Send emails</button>
                                <a href = "{{ action('Admin\InvitationController@send') }}" class="btn btn-secondary">Cancel</a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            var t = $('#example').DataTable({
                "pagingType": "full_numbers"
            });
            t.on('order.dt search.dt', function () {
                t.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        });
    </script>
@endsection
