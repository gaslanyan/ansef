@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                <div class="card" >

                        <div class="card-header">{{ucfirst($type)}} list
                            @if(get_role_cookie() == 'superadmin')
                            <a href="{{action('Admin\AccountController@create')}}"
                               class="display float-lg-right btn-primary px-2 myButton"><i class="fas fa-plus"></i>&nbsp;Add a person</a>
                            @endif
                        </div>
                    <div class="card-body card_body" style="overflow:auto;">
                        @include('partials.status_bar')
                        @if($type == 'applicant')
                        <p>
                        @foreach($competitions as $comp)
                        - <a style="color:#{{$comp->id == $cid ? 'f00' : '999'}};" href="{{action('Admin\AccountController@account',['type' => 'applicant', 'cid' => $comp->id])}}">{{$comp->title}}</a>
                        @endforeach
                        </p>
                        @endif
                        @if(!empty($persons) && count($persons)>0)

                            <table class="table table-responsive-md table-sm table-bordered display" id="example"
                                   style="width:100%">
                                <thead>
                                <tr>
                                    <th width="20px"></th>
                                    <th width="100px">First Name</th>
                                    <th width="100px">Last Name</th>
                                    <th width="100px">Email</th>
                                    <th># @if($type == 'applicant') &laquo;award&raquo;&laquo;finalist&raquo; @endif</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($persons as $p)
                                    <tr>
                                        <td></td>
                                        <td data-order="@if(!empty($p['first_name'])){{$p['first_name']}}@endif"
                                            data-search="@if(!empty($p['first_name'])){{$p['first_name']}}@endif"
                                            class="f_name_field">
                                                {{$p['first_name']}}
                                        </td>
                                        <td data-order="@if(!empty($p['last_name'])){{$p['last_name']}} @endif"
                                            data-search="@if(!empty($p['last_name'])){{$p['last_name']}} @endif"
                                            class="l_name_field">
                                                {{$p['last_name']}}
                                        </td>
                                        <td data-order="@if(!empty($p['email'])) {{$p['email']}}@endif"
                                            data-search="@if(!empty($p['email'])) {{$p['email']}}@endif"
                                            class="">
                                            <a href="mailto:{{$p['email']}}"><span style="color:#09b;">{{$p['email']}}</span></a>
                                        </td>
                                        <td>
                                            #:{{$p['propcount']}}
                                            - {{$p['subtype']}} -
                                            <b>{{$p['awards']}}</b> {{$p['finalists']}}
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
