@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                <div class="card" style="margin-top:20px;">

                        <div class="card-header">{{ucfirst($type)}} list
                            @if(get_Cookie() == 'superadmin')
                            <a href="{{action('Admin\AccountController@create')}}"
                               class="display float-lg-right btn-primary px-2">Add a person</a>
                            @endif
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
                        @if(!empty($persons))

                            <table class="table table-responsive-md table-sm table-bordered display" id="example"
                                   style="width:100%">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Institution</th>
                                    @if($type !== "applicant")
                                        <th>State</th>
                                    @else
                                        <th>Role</th>
                                    @endif
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($persons as $key=>$pp)
                                    @foreach($pp as $key=>$p)

                                        {{--                                        @php echo "<pre>"; var_dump($p); @endphp--}}
                                        <tr>
                                            <td></td>
                                            <td data-order="@if(!empty($p['first_name'])){{$p['first_name']}}@endif"
                                                data-search="@if(!empty($p['first_name'])){{$p['first_name']}}@endif"
                                                class="f_name_field">
                                                @if(!empty($p['first_name']))
                                                    <input type="text" class="form-control" name="first_name"
                                                           value="{{$p['first_name']}}" disabled>
                                                @endif
                                            </td>
                                            <td data-order="@if(!empty($p['last_name'])){{$p['last_name']}} @endif"
                                                data-search="@if(!empty($p['last_name'])){{$p['last_name']}} @endif"
                                                class="l_name_field">
                                                @if(!empty($p['last_name']))
                                                    <input type="text" class="form-control" name="last_name"
                                                           value="{{$p['last_name']}}" disabled>
                                                @endif
                                            </td>
                                            <td data-order="@if(!empty($p['user']['email'])){{$p['user']['email']}}@else {{$p['email']}}@endif"
                                                data-search="@if(!empty($p['user']['email'])){{$p['user']['email']}}@else {{$p['email']}}@endif"
                                                class="email_field">
                                                <input type="text" class="form-control" name="email"
                                                       value="@if(!empty($p['user']['email'])){{$p['user']['email']}}@else {{$p['email']}}@endif"
                                                       disabled></td>

                                            @php
                                                if (!empty($ip[$p['id']]))
                                                 $content = $ip[$p['id']]['iperson']['content'];
                                            @endphp
                                            <td data-order="@if (!empty($ip[$p['id']])){{$content}} @else {{'Select Institution'}} @endif"
                                                data-search="@if (!empty($ip[$p['id']])){{$content}} @else {{'Select Institution'}} @endif"
                                                class="content_field">
                                                @if (!empty($ip[$p['id']]))
                                                    <select class="form-control" name="content" disabled>
                                                        <option>Select Institution</option>
                                                        @if(!empty($institutions))
                                                            @foreach($institutions as $item)
                                                                <option class="text-capitalize"
                                                                        value="{{$item->content}}" @if(!empty($ip[$p['id']]) && $item->content === $content) {{'selected'}} @endif>
                                                                    {{$item->content}}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                @endif
                                            </td>
                                            @if($type !== "applicant")
                                                <td data-order="@if (!empty($p['user']['state'])){{$p['user']['state']}} @else {{$p['state']}}@endif"
                                                    data-search="@if (!empty($p['user']['state'])){{$p['user']['state']}} @else {{$p['state']}}@endif"
                                                    class="state_field">

                                                    <select class="form-control" name="state" disabled>
                                                        <?php $enum = getEnumValues('users', 'state');?>
                                                        @if(!empty($enum))
                                                            <option value="0">Select state</option>

                                                            @foreach($enum as $item)
                                                                <option class="text-capitalize"
                                                                        value="{{$item}}"
                                                                @if((!empty($p['user']['state'])
                                                                          && $item === $p['user']['state'])
                                                                          || $item === $p['state'])
                                                                    {{'selected'}}
                                                                        @endif>
                                                                    {{$item}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </td>
                                            @else
                                                <td ddata-order="@if(!empty($p['type'])){{$p['type']}} @endif"
                                                data-search="@if(!empty($p['type'])){{$p['type']}} @endif"
                                                    class="state_field">
                                                    <span style="color:#777;">{{$p['type'] == 'contributor' ? 'Participant' : 'Support'}}</span>
                                                </td>
                                            @endif
                                            <td>

                                                <input type="hidden" class="id"
                                                       value="@if(!empty($p['user']['id'])){{$p['user']['id']}} @else {{$p['id']}} @endif">
                                                <input type="hidden" class="url" value="/updateAcc">
                                                {{--@if(!empty($p['user']['id']))--}}
                                                @if($type !== "applicant")
                                                <button title="Edit"
                                                        class="edit btn-link"><i class="fa fa-pencil-alt"></i>
                                                </button>
                                                <button title="Save"
                                                        class="save editable btn-link"><i class="fa fa-save"></i>
                                                </button>
                                                <button title="Cancel"
                                                        class="cancel editable btn-link"><i class="fa fa-ban"></i>
                                                </button>
                                                @endif
                                                {{--@endif--}}
                                                @if($type =="referee" )
                                                    @if(isset($p['user']['id']))
                                                        <a target="_blank" href="{{action(ucfirst($type).'\\'.ucfirst($type).'Controller@index',
                                                          $p['user']['id'])}}"
                                                           class="login" title="Login"><i class="fa fa-sign-in-alt"></i>
                                                        </a>
                                                        <a href="{{action('Admin\AccountController@mailreferee', ['id'=>$p['user']['id']])}}"
                                                           class="mail" title="Mail"><i class="fas fa-envelope"></i>
                                                        </a>
                                                        <input type="hidden" class="id" name="{{$type}}"
                                                               value="@if(!empty($p['user']['id'])){{$p['user']['id']}} @else {{$p['id']}} @endif">
                                                    @endif
                                                @endif
                                                @if($type =="viewer" )
                                                    @if(isset($p['user']['id']))
                                                        <a href="{{action('Admin\AccountController@mailviewer', ['id'=>$p['user']['id']])}}"
                                                           class="mail" title="Mail"><i class="fas fa-envelope"></i>
                                                        </a>
                                                        <input type="hidden" class="id" name="{{$type}}"
                                                               value="@if(!empty($p['user']['id'])){{$p['user']['id']}} @else {{$p['id']}} @endif">
                                                    @endif
                                                @endif
                                                @php
                                                    if(!empty($p['user']['id']))
                                                    $id = $p['user']['id'];
                                                else
                                                $id =$p['id'];

                                                @endphp
                                                @if(get_Cookie() == 'superadmin')
                                                    <form action="{{action('Admin\PersonController@destroy', ['id'=>$id, 'type'=>$type])}}"
                                                          method="post">
                                                        @csrf
                                                        <input name="_method" type="hidden" value="DELETE">
                                                        <input name="_id" type="hidden" value="{{$id}}">
                                                        <button class="btn-link delete" type="button"
                                                                data-title="{{$type}}">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>

                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
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
    <script>
        $(document).ready(function () {
            var t = $('#example').DataTable({
                "pagingType": "full_numbers",

            });
            t.on('order.dt search.dt', function () {
                t.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        });

    </script>

@endsection
