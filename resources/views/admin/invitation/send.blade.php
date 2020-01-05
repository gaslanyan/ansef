@extends('layouts.master')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Send emails
                </div>

                <div class="card-body" style="overflow:auto;">
                    <i class="fas fa-question-circle text-blue all"> </i>&nbsp;<i
                        class="text-blue">{{Lang::get('messages.required_all')}}</i>
                    @include('partials.status_bar')

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
                                        <option value="{{$message->id}}" @if(old('template')==$message->id)
                                            {{'selected'}} @endif>{{$message->text}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group col-lg-12">
                                    <table class="table table-responsive-md table-sm table-bordered display"
                                        id="example" style="width:100%">
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
                                                            value="{{$user->email}}" id="email{{$user->id}}">
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
            "dom": '<"top"flp>rt<"bottom"i><"clear">',
            "pagingType": "full_numbers"
        });
        t.on('order.dt search.dt', function () {
            t.column(0, {
                search: 'applied',
                order: 'applied'
            }).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();
    });

</script>
@endsection
