@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                <div class="card" >
                        @php
                        if (Cookie::get('cid') !== null)
                            $cid = Cookie::get('cid');
                        else {
                            $cid = empty(\App\Models\Competition::latest('created_at')->first()) ? -1 : \App\Models\Competition::latest('created_at')->first()->id;
                        }
                        @endphp

                        <div class="card-header">{{ucfirst($type)}} list - {{ucfirst($subtype)}}
                            @if($type == 'applicant')
                            - competition <select name="competition" id="competition" style="width:100px;font-size:24px;">
                                @foreach($competitions as $c)
                                    <option value="{{$c['id']}}" {{$c['id']==$cid ? 'selected' : ''}}>{{$c['title']}}</option>
                                @endforeach
                                    <option value="-1" {{-1==$cid ? 'selected' : ''}}>All</option>
                            </select>
                            @else
                            <input type="hidden" name="competition" id="competition" value="0">
                            @endif
                            <input type="hidden" name="subtype" id="subtype" value="{{$subtype}}">
                            <input type="hidden" name="type" id="type" value="{{$type}}">
                            @if(get_role_cookie() == 'superadmin')
                            <a href="{{action('Admin\AccountController@create')}}"
                               class="display float-lg-right btn-primary px-2 myButton"><i class="fas fa-plus"></i>&nbsp;Add a person</a>
                            @endif
                        </div>
                    <div class="card-body card_body" style="overflow:auto;">
                        @include('partials.status_bar')
                            <table class="table table-bordered display compact" id="datatable">
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
                                </tbody>
                                        <td>

                                        </td>
                            </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script>
        $(document).ready(function () {
            setCookie("cid", "{{$cid}}", 2);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var t = $('#datatable').DataTable({
                    "pagingType": "full_numbers",
                    "columns": [
                        {"data": "first_name"},
                        {"data": "last_name"},
                        {
                            "render": function (data, type, full, meta) {
                                var email = full.email;
                                return '<a href="mailto:' + email + '"><span style="color:#09b;">' + email + '</span></a>';
                            }
                        }
                        {
                            "render": function (data, type, full, meta) {
                                return '#:' + full.propcount + ' - <b>' + full.awards + '</b> ' + full.awards;
                            }
                        }
                    ],
                    "columnDefs": [
                    { "width": "100px", "targets": 0, "searchable": true, "orderable": true, "visible": true },
                    { "width": "150px", "targets": 1, "searchable": true, "orderable": true, "visible": true },
                    { "width": "150px", "targets": 2, "searchable": true, "orderable": true, "visible": true },
                    { "width": "500px", "targets": 3, "searchable": true, "orderable": true, "visible": true }
                ],
                "select": false,
                "scrollX": true,
                "scrollY": 450,
                "deferRender": true,
                "scrollCollapse": false,
                "scroller": true,
                "colReorder": false,
                "processing": true,
                "language": {
                    "loadingRecords": '&nbsp;',
                    "processing": 'Loading...'
                },
                "dom": 'Bfrtip',
                "buttons": [
                    'selectAll', 'selectNone', 'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });

            reloadtable('admin/listpersons');
            $('#competition').change(function() {
                reloadtable('admin/listpersons');
            });
            // t.on('order.dt search.dt', function () {
            //     t.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
            //         cell.innerHTML = i + 1;
            //     });
            // }).draw();
        });

    </script>

@endsection
