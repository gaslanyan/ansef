@extends('layouts.master')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                @php
                if (Cookie::get('cid') !== null)
                $cid = Cookie::get('cid');
                else {
                $cid = empty(\App\Models\Competition::latest('created_at')->first()) ? -1 :
                \App\Models\Competition::latest('created_at')->first()->id;
                }
                @endphp

                <div class="card-header">List of proposals for competition :
                    <select name="competition" id="competition" style="width:100px;font-size:24px;">
                        @foreach($competitions as $c)
                        <option value="{{$c['id']}}" {{$c['id']==$cid ? 'selected' : ''}}>{{$c['title']}}</option>
                        @endforeach
                        <option value="-1" {{-1==$cid ? 'selected' : ''}}>All</option>
                    </select>
                </div>
                <div class="card-body ajaxdiv" style="overflow:auto;">
                    @include('partials.status_bar')

                    <div class="col-12" style="margin-bottom:20px;padding-bottom:35px;">
                        <button type="button" title="change state" onclick="open_container('state');"
                            class="display float-lg-left btn-primary px-2 myButton">
                            <i class="fa fa-comments"></i>
                            Change state
                        </button>
                        <button type="button" title="send email" onclick="open_container('email');"
                            class="display float-lg-left btn-primary px-2 myButton">
                            <i class="fa fa-envelope-open"></i>
                            Send Email
                        </button>
                        @if(get_role_cookie() === "superadmin")
                        <button type="button" title="check" onclick="check();"
                            class="display float-lg-left btn-primary px-2 myButton">
                            <i class="fas fa-check"></i>
                            Validate
                        </button>
                        <button type="button" title="add admin" onclick="open_container('admin');"
                            class="display float-lg-left btn-primary px-2 myButton">
                            <i class="fa fa-user-graduate"></i>
                            Assign Admin
                        </button>
                        @endif
                        @if(get_role_cookie() === "superadmin" || get_role_cookie() === "admin" )
                        <button type="button" title="add referee" onclick="open_container('referee');"
                            class="display float-lg-left btn-primary px-2 myButton">
                            <i class="fas fa-balance-scale"></i>
                            Add Referee
                        </button>
                        @endif
                        @if(get_role_cookie() == 'superadmin')
                        <button type="button" title="delete" onclick="deleteproposals();"
                            class="display float-lg-right btn-primary px-2 myButton">
                            <i class="fa fa-trash-alt"></i>
                            Delete
                        </button>
                        @endif
                    </div>
                    <table class="table table-bordered display compact" id="datatable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Proposal Title</th>
                                <th>Proposal PI</th>
                                <th>Referee</th>
                                <th>Admin</th>
                                <th width="100px">State</th>
                                <th>Score</th>
                                <th>Rank</th>
                                <th class="action long">Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>

                </div>

            </div>
        </div>
    </div>
</div>
<!-- Modal form-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h2></h2>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                        class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body" id="modal-bodyku">
                <div id="referee">

                    @if(!empty($referees))
                    @foreach($referees as $i=>$r)
                    <div class="row">
                        <div class="form-group col-lg-1">
                            <label for="choose_person_name{{$i}}" class="label">
                                <input type="checkbox" name="choose_person[]" id="choose_person_name{{$i}}"
                                    value="{{$r['id']}}" />
                            </label>
                        </div>
                        <div class="form-group col-lg-9">
                            {{$r['last_name']." ".$r['first_name']}} ({{$r->user->email}})
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
                <div id="admin">
                    @if(!empty($admins))
                    @foreach($admins as $i=>$a)
                    <div class="row">
                        <div class="form-group col-lg-1">
                            <label for="choose_person_name{{$i}}" class="label">
                                <input type="checkbox" name="choose_person[]" id="choose_person_name{{$i}}"
                                    value="{{$a['id']}}" />
                            </label>
                        </div>
                        <div class="form-group col-lg-5">
                            {{$a['first_name']." ".$a['last_name']}}
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
                <div id="email">
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label for="message" class="label"></label>
                            <select name="message" id="message">
                                <option>Select template</option>
                                @if(!empty($messages))
                                @foreach($messages as $m)
                                <option value="{{$m->id}}" subject="{{$m->subject}}" content="{{$m->text}}">
                                    {{$m->title}}</option>
                                @endforeach
                                @endif
                            </select>
                            <br /><br />
                            <div class="row">
                                <div class="form-group col-12">
                                    <label for="subject">Subject:</label>
                                    <input class="form-control" type="text" name="subject" id="subject">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-12">
                                    <label for="messagecontent">Message:</label><br />
                                    <textarea name="messagecontent" id="messagecontent" cols="70" rows="5"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="state">
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label for="change_proposal_state" class="label">
                                <select class="form-control" name="change_proposal_state">
                                    <option value="0">Select state</option>
                                    @if(!empty($enumvals))
                                    @foreach($enumvals as $item)
                                    <option class="text-capitalize" value="{{$item}}">{{$item}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </label>

                        </div>
                    </div>

                </div>
                <div class="modal-footer" id="modal-footerq">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close
                    </button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="choose">Choose
                    </button>
                </div>
                <input type="hidden" class="form-control form-check-inline aaa" name="hidden_choose_person[]" value=""
                    id="aaa">
                {{--</form>--}}
            </div>
        </div>
    </div>
</div>
</div>
<!-- end of modal ------------------------------>

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
            "columns": [{
                    "data": "tag"
                },
                {
                    "data": "title"
                },
                {
                    "data": "pi"
                },
                {
                    "data": "refs"
                },
                {
                    "data": "admin"
                },
                {
                    "data": "state"
                },
                {
                    "data": "score"
                },
                {
                    "data": "rank"
                },
                {
                    "render": function (data, type, full, meta) {
                        var ID = full.id;
                        return '<form action= "<?= action('Admin\ProposalController@display', '')?>" method="post"> ' +
                            '<input name="_method" type="hidden" value="POST">' +
                            '<input type="hidden" name="_token" value="{!! csrf_token() !!}">' +
                            '<input name="id" type="hidden" value="' + ID + '">' +
                            '<button class="btn btn-link" type="submit">' +
                            '<i class="fa fa-eye"></i></button></form>';
                    }
                }
            ],
            "columnDefs": [{
                    "width": "120px",
                    "targets": 0,
                    "searchable": true,
                    "orderable": true,
                    "visible": true
                },
                {
                    "width": "175px",
                    "targets": 1,
                    "searchable": true,
                    "orderable": true,
                    "visible": true
                },
                {
                    "width": "120px",
                    "targets": 2,
                    "searchable": true,
                    "orderable": true,
                    "visible": true
                },
                {
                    "width": "120px",
                    "targets": 3,
                    "searchable": true,
                    "orderable": true,
                    "visible": true
                },
                {
                    "width": "120px",
                    "targets": 4,
                    "searchable": true,
                    "orderable": true,
                    "visible": true
                },
                {
                    "width": "100px",
                    "targets": 5,
                    "searchable": true,
                    "orderable": true,
                    "visible": true
                },
                {
                    "width": "40px",
                    "targets": 6,
                    "searchable": true,
                    "orderable": true,
                    "visible": true
                },
                {
                    "width": "40px",
                    "targets": 7,
                    "searchable": true,
                    "orderable": true,
                    "visible": true
                },
                {
                    "width": "40px",
                    "targets": 8,
                    "searchable": true,
                    "orderable": true,
                    "visible": true
                }
            ],
            "select": true,
            "scrollX": true,
            "scrollY": 300,
            "deferRender": true,
            "scrollCollapse": false,
            "scroller": true,
            "colReorder": true,
            // "fixedColumns":   { "leftColumns": 1 },
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
        // t.on('order.dt search.dt', function () {
        //     t.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
        //         cell.innerHTML = i + 1;
        //     });
        // }).draw();

        reloadtable('admin/listproposals');
        $('#competition').change(function () {
            reloadtable('admin/listproposals');
        });
        $('#message').change(function () {
            var subject = $('option:selected', this).attr('subject');
            var content = $('option:selected', this).attr('content');
            $('#subject').val(subject);
            $('#messagecontent').val(content);
        });

    });

    function check() {
        var t = $('#datatable').DataTable();
        var data = t.rows({
            'selected': true
        }).data();
        if (data.length > 0) {
            var checkedIDss = [];
            for (var i = 0; i < data.length; i++) {
                checkedIDss.push(data[i].id);
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/admin/checkProposal',
                type: 'POST',
                data: {
                    token: CSRF_TOKEN,
                    id: checkedIDss
                },
                dataType: 'JSON',
                success: function (data) {
                    if (data.success === -1)
                        console.log('msg' + data);
                    else
                        reloadtable('admin/listproposals');
                },
                error: function (data) {
                    console.log('msg' + data);
                }
            });
        }
    }

    function deleteproposals() {
        var t = $('#datatable').DataTable();
        var data = t.rows({
            'selected': true
        }).data();
        if (data.length > 0) {
            if (confirm('Are you sure you want to delete ' + data.length + ' proposals?')) {
                var checkedIDss = [];
                for (var i = 0; i < data.length; i++) {
                    checkedIDss.push(data[i].id);
                }
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/admin/deleteProposal',
                    type: 'POST',
                    data: {
                        token: CSRF_TOKEN,
                        id: checkedIDss
                    },
                    dataType: 'JSON',
                    success: function (data) {
                        if (data.success === -1)
                            console.log('msg' + data);
                        else
                            reloadtable('admin/listproposals');
                    },
                    error: function (data) {
                        console.log('msg' + data);
                    }
                });
            }
        }
    }

    function change_state(checkedIDss) {
        var selected = $('[name=change_proposal_state]').val();
        if (selected)
            $.ajax({
                url: '/admin/changeState',
                type: 'POST',
                data: {
                    _token: CSRF_TOKEN,
                    state: selected,
                    ids: JSON.stringify(checkedIDss)
                },
                dataType: 'JSON',
                success: function (data) {
                    reloadtable('admin/listproposals');
                },
                error: function (data) {
                    console.log(data);
                }
            });
        else
            alert('Please choose a proposal');
    }

    function send_email(checkedIDss, subject, content) {
        if (content != "" && subject != "") {
            $.ajax({
                url: '/admin/sendEmail',
                type: 'POST',
                data: {
                    _token: CSRF_TOKEN,
                    ids: JSON.stringify(checkedIDss),
                    subject: subject,
                    content: content
                },
                dataType: 'JSON',
                success: function (data) {
                    alert(data);
                },
                error: function (data) {
                    alert('Error occured. No emails were sent.');
                    console.log(data);
                }
            });
        } else
            alert('Please Choose Proposal!')

    }

    var _type = '';

    function open_container(type) {
        var size = 'small',
            content = '',
            title = 'Choose a ' + type,
            footer = '';
        _type = type;
        jQuery.noConflict();
        setModalBox(title, size);

        var t = $('#datatable').DataTable();
        data = t.rows({
            'selected': true
        }).data();
        if (data.length > 0)
            jQuery('#myModal').modal('show');
        else
            alert('Please choose a proposal.')
    }

    function setModalBox(title, content, footer, $size) {
        jQuery('#myModal').find('.modal-header h2').text(title);
        $('#admin, #referee, #email, #state').css('display', 'none');
        $('#' + _type).css('display', 'block');
        if ($size === 'small') {
            jQuery('#myModal').attr('class',
                    'modal fade bs-example-modal-sm')
                .attr('aria-labelledby', 'mySmallModalLabel');
            jQuery('.modal-dialog').attr('class', 'modal-dialog modal-sm');
        }
    }

    jQuery('#choose').on('click', function () {
        var checkedIDss = [],
            checkedIDs = [];
        jQuery(".modal-body input:checkbox:checked").each(function () {
            checkedIDs.push($(this).val());
        });
        var t = $('#datatable').DataTable();
        data = t.rows({
            'selected': true
        }).data();
        for (var i = 0; i < data.length; i++) {
            checkedIDss.push(data[i].id);
        }
        var CSRF_TOKEN = $('input[name="_token"]').val();
        if (_type === "email") {
            var content = $('#messagecontent').val();
            var subject = $('#subject').val();
            send_email(checkedIDss, subject, content);
        } else if (checkedIDss.length > 0 && checkedIDs.length > 0) {
            jQuery.ajax({
                url: '/admin/addUsers',
                type: 'POST',
                context: {
                    element: $(this)
                },
                data: {
                    _token: CSRF_TOKEN,
                    p_ids: JSON.stringify(checkedIDss),
                    u_ids: JSON.stringify(checkedIDs),
                    type: _type
                },
                dataType: 'JSON',
                success: function (data) {
                    console.log(data);
                    reloadtable('admin/listproposals');
                },
                error: function (data) {
                    console.log(data);
                }
            });
        } else if (_type === "state") {
            change_state(checkedIDss);
        } else
            alert('Please Choose ' + _type + '!')
    });

</script>

@endsection
