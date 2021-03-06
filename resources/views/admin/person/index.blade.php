@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">List of all users
                    @if(get_role_cookie() == 'superadmin')
                    <a href="{{action('Admin\AccountController@create')}}"
                        class="display float-lg-right btn-primary px-2 myButton"><i class="fas fa-plus"></i>&nbsp;Add a
                        person</a>
                    @endif
                </div>
                <div class="card-body" style="overflow:auto;">
                    @include('partials.status_bar')

                    <table class="table table-responsive-md table-sm table-bordered display compact" id="example"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($users))
                            @foreach($users as $user)

                            <tr>
                                <td data-order="data-order='[[ 1, &quot;asc&quot; ]]'"></td>
                                <td data-order="{{$user->email}}" data-search="{{$user->email}}" class="email_field">
                                    <input type="text" class="form-control" name="email" value="{{$user->email}}"
                                        disabled>
                                </td>

                                <td data-order="{{$user->role->name}}" data-search="{{$user->role->name}}"
                                    class="status_field">
                                    <select class="form-control" name="status" disabled>
                                        @if(!empty($roles))
                                        @foreach($roles as $item)
                                        <option class="text-capitalize" value="{{$item->id}}" @if($item->name ===
                                            $user->role->name) {{'selected'}} @endif>{{$item->name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </td>

                                <td data-order="{{$user->state}}" data-search="{{$user->state}}" class="type_field">
                                    <select class="form-control" name="state" disabled>
                                        <?php $enum = getEnumValues('users', 'state');?>
                                        <option value="0">Select type</option>
                                        @if(!empty($enum))
                                        @foreach($enum as $item)
                                        <option class="text-capitalize" value="{{$item}}" @if(!empty($item) &&
                                            $item===$user->state ) {{'selected'}} @endif >{{$item}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    <input type="hidden" class="id" value="{{$user->id}}">
                                    <input type="hidden" class="url" value="/admin/updatePerson">
                                    <button title="Edit" class="edit btn-link"><i class="fa fa-pencil-alt"></i>
                                    </button>
                                    <button title="Save" class="save editable btn-link"><i
                                            class="fa fa-save"></i></button>
                                    <button title="Cancel" class="cancel editable btn-link"><i
                                            class="fa fa-ban"></i></button>
                                    <form action="{{action('Admin\AccountController@generatePassword', $user->id)}}"
                                        method="post">
                                        @csrf
                                        <button title="generate password" class="password btn-link" type="submit">
                                            <i class="fa fa-lock"></i>
                                        </button>
                                    </form>

                                    <a href="{{action('Admin\AccountController@show', $user->id)}}" class="view"
                                        title="View"><i class="fa fa-eye"></i>
                                    </a>
                                    @if($user->role->name == 'applicant' || $user->role->name == 'referee')
                                    <a href="{{URL('/admin/'.$user->role->name.'/signas/'.$user->id )}}">
                                        <i class="fa fa-sign-in-alt"></i>
                                    </a>
                                    @endif
                                    @if(get_role_cookie() == 'superadmin')
                                    <form
                                        action="{{action('Admin\AccountController@destroy', ['id'=>$user->id, 'type'=>$user->role->name])}}"
                                        method="post">
                                        @csrf
                                        <input name="_method" type="hidden" value="DELETE">
                                        <input name="_id" type="hidden" value="{{$user->id}}">
                                        <button class="btn-link delete" type="button"
                                            data-title="{{$user->role->name}}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </td>

                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>

                </div>

            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        var t = $('#example').DataTable({
            "dom": '<"top"flp>rt<"bottom"i><"clear">',
            "pagingType": "full_numbers",
            columnDefs: [{
                targets: [0],
                orderData: [0, 1]
            }, {
                targets: [1],
                orderData: [1, 0]
            }]
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
