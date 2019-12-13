@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" >
                    <div class="card-header">List of all persons
                        @if(get_role_cookie() == 'superadmin')
                            <a href="{{action('Admin\AccountController@create')}}"
                               class="display float-lg-right btn-primary px-2 myButton">Add a person</a>
                        @endif
                    </div>
                    <div class="card-body card_body" style="overflow:auto;">
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
                            @if(!empty($persons))
                                @foreach($persons as $person)

                                    <tr>
                                        <td data-order="data-order='[[ 1, &quot;asc&quot; ]]'"></td>
                                        <td data-order="{{$person->email}}"
                                            data-search="{{$person->email}}"
                                            class="email_field">
                                            <input type="text" class="form-control" name="email"
                                                   value="{{$person->email}}" disabled>
                                        </td>

                                        <td data-order="{{$person->role->name}}"
                                            data-search="{{$person->role->name}}"
                                            class="status_field">
                                            <select class="form-control" name="status" disabled>
                                                @if(!empty($roles))
                                                    @foreach($roles as $item)
                                                        <option class="text-capitalize"
                                                                value="{{$item->id}}" @if($item->name === $person->role->name) {{'selected'}} @endif>{{$item->name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </td>

                                        <td data-order="{{$person->state}}" data-search="{{$person->state}}"
                                            class="type_field">
                                            <select class="form-control" name="state" disabled>
                                                <?php $enum = getEnumValues('users', 'state');?>
                                                <option value="0">Select type</option>
                                                @if(!empty($enum))
                                                    @foreach($enum as $item)
                                                        <option class="text-capitalize" value="{{$item}}"
                                                        @if(!empty($item) && $item === $person->state ) {{'selected'}} @endif >{{$item}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </td>
                                        <td>
                                            <input type="hidden" class="id" value="{{$person->id}}">
                                            <input type="hidden" class="url" value="/admin/updatePerson">
                                            <button title="Edit" class="edit btn-link"><i class="fa fa-pencil-alt"></i>
                                            </button>
                                            <button title="Save" class="save editable btn-link"><i
                                                        class="fa fa-save"></i></button>
                                            <button title="Cancel" class="cancel editable btn-link"><i
                                                        class="fa fa-ban"></i></button>

                                            <form action="{{action('Admin\AccountController@generatePassword', $person->id)}}"
                                                  method="post">
                                                @csrf
                                                <button title="generate password" class="password btn-link"
                                                        type="submit">
                                                    <i class="fa fa-lock"></i>
                                                </button>
                                            </form>

                                            @if(getPerson($person->id))
                                                <a href="{{action('Admin\AccountController@show', $person->id)}}"
                                                   class="view" title="View"><i class="fa fa-eye"></i>
                                                </a>
                                            @endif
                                            @if($person->role->name == 'applicant' || $person->role->name == 'referee')

                                                <a target="_blank" href="{{action(ucfirst($person->role->name).'\\'.ucfirst($person->role->name).'Controller@index',
                                                          $person->id)}}"
                                                   class="login" title="Login"><i class="fa fa-sign-in-alt"></i>
                                                </a>
                                                <input type="hidden" class="id" name="{{$person->role->name}}"
                                                       value="{{$person->id}}">
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
                t.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        });

    </script>

@endsection
