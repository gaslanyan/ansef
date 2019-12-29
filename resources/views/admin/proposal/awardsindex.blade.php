@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" >
                    <div class="card-header">List of awards for competition :
                        <select name="competition" id="competition" style="width:100px;font-size:24px;">
                            @foreach($competitions as $c)
                                <option value="{{$c['id']}}" {{$c['id']==$cid ? 'selected' : ''}}>{{$c['title']}}</option>
                            @endforeach
                                <option value="-1" {{-1==$cid ? 'selected' : ''}}>All</option>
                        </select>
                    </div>
                    <div class="card-body card_body" style="overflow:auto;">
                        @include('partials.status_bar')

                        <div class="col-12" style="margin-bottom:20px;padding-bottom:35px;">
                                <button type="button"
                                        title="change state" onclick="open_container('state');"
                                        class="display float-lg-left btn-primary px-2 myButton">
                                        <i class="fa fa-comments"></i>
                                    Change state
                                </button>

                                <button type="button"
                                        title="send email" onclick="open_container('email');"
                                        class="display float-lg-left btn-primary px-2 myButton">
                                        <i class="fa fa-envelope-open"></i>
                                    Send Email
                                </button>
                        </div>
                        <table class="table table-bordered display compact" id="datatable">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Proposal Title</th>
                                <th>Proposal PI</th>
                                <th>State</th>
                                <th>Score</th>
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
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h3></h3>
                    <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span
                                class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel"></h4>
                </div>
                <div class="modal-body" id="modal-bodyku">
                    <div id="email">
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label for="message" class="label"></label>
                                        <select name="message" id="message">
                                            <option>Select template</option>
                                            @if(!empty($messages))
                                            @foreach($messages as $m)
                                            <option value="{{$m->id}}" subject="{{$m->subject}}" content="{{$m->text}}">{{$m->title}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    <br/><br/>
                                    <div class="row">
                                    <div class="form-group col-12">
                                        <label for="subject">Subject:</label>
                                        <input class="form-control" type="text" name="subject" id="subject">
                                    </div>
                                    </div>
                                    <div class="row">
                                    <div class="form-group col-12">
                                        <label for="messagecontent">Message:</label><br/>
                                        <textarea name="messagecontent" id="messagecontent" cols="70" rows="5"></textarea>
                                    </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div id="state">
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label for="change_proposal_state"
                                       class="label">
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
                        <button type="button" class="btn btn-primary"
                                data-dismiss="modal" id="choose">Choose
                        </button>
                    </div>
                    <input type="hidden" class="form-control form-check-inline aaa"
                           name="hidden_choose_person[]" value="" id="aaa">
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
                    "columns": [
                        {"data": "tag"},
                        {"data": "title"},
                        {"data": "pi"},
                        {"data": "state"},
                        {"data": "score"},
                        {
                            "render": function (data, type, full, meta) {
                                var ID = full.id;
                                var viewbutton = '<form action= "<?= action('Admin\ProposalController@display', '')?>" method="post"> ' +
                                    '<input name="_method" type="hidden" value="POST">' +
                                    '<input type="hidden" name="_token" value="{!! csrf_token() !!}">'+
                                    '<input name="id" type="hidden" value="' + ID + '">' +
                                    '<button class="btn btn-link myButton" type="submit">' +
                                    '<i class="fa fa-eye"></i></button></form>';
                                var firstreportbutton = '';
                                if(full['first'])
                                    firstreportbutton = '<form action= "<?= action('Admin\ProposalController@downloadfirstreport', '')?>" method="post"> ' +
                                    '<input name="_method" type="hidden" value="POST">' +
                                    '<input type="hidden" name="_token" value="{!! csrf_token() !!}">'+
                                    '<input name="id" type="hidden" value="' + ID + '">' +
                                    '<button class="btn btn-link myButton" type="submit">' +
                                    '<i class="fas fa-italic "></i></button></form>';

                                var secondreportbutton = '';
                                if(full['second'])
                                    secondreportbutton = '<form action= "<?= action('Admin\ProposalController@downloadsecondreport', '')?>" method="post"> ' +
                                    '<input name="_method" type="hidden" value="POST">' +
                                    '<input type="hidden" name="_token" value="{!! csrf_token() !!}">'+
                                    '<input name="id" type="hidden" value="' + ID + '">' +
                                    '<button class="btn btn-link myButton" type="submit">' +
                                    '<i class="fas fa-italic"></i><i class="fas fa-italic"></i></button></form>';

                                return  '<div style="margin:0px;padding:0px;">' + viewbutton +
                                        firstreportbutton +
                                        secondreportbutton + '</div>';
                            }
                        }
                    ],
                    "columnDefs": [
                    { "width": "120px", "targets": 0, "searchable": true, "orderable": true, "visible": true },
                    { "width": "175px", "targets": 1, "searchable": true, "orderable": true, "visible": true },
                    { "width": "150px", "targets": 2, "searchable": true, "orderable": true, "visible": true },
                    { "width": "100px", "targets": 3, "searchable": true, "orderable": true, "visible": true },
                    { "width": "120px", "targets": 4, "searchable": true, "orderable": true, "visible": true },
                    { "width": "300px", "targets": 5, "searchable": true, "orderable": true, "visible": true },
                ],
                "select": true,
                "scrollX": true,
                "scrollY": 450,
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

            reloadtable('admin/listawards');
            $('#competition').change(function() {
                reloadtable('admin/listawards');
            });
            $('#message').change(function() {
                var subject = $('option:selected', this).attr('subject');
                var content = $('option:selected', this).attr('content');
                $('#subject').val(subject);
                $('#messagecontent').val(content);
            });


        });

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
            success: function(data) {
                reloadtable('admin/listawards');
            },
            error: function(data) {
                console.log(data);
            }
        });
    else
        alert('Please choose a proposal');
}

function send_email(checkedIDss, subject, content) {
    if (content != "" && subject != "") {
        // alert(subject + ': ' + content);
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
            success: function(data) {
                alert(data);
            },
            error: function(data) {
                alert('Error occured. No emails were sent.');
                console.log(data);
            }
        });
    }
    else
        alert('Please Choose Proposal!')

}

        var _type = '';

        function open_container(type) {
            var size = 'small',
                content = '',
                title = 'Compose message',
                footer = '';
            _type = type;
            jQuery.noConflict();
            setModalBox(title, size);

            var t = $('#datatable').DataTable();
            data = t.rows({'selected': true}).data();
            if (data.length > 0)
                jQuery('#myModal').modal('show');
            else
                alert('Please choose a proposal.')
        }

        function setModalBox(title, content, footer, $size) {
            jQuery('#myModal').find('.modal-header h3').text(title);
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
            data = t.rows({'selected': true}).data();
            for(var i=0; i<data.length; i++) {
                checkedIDss.push(data[i].id);
            }
            var CSRF_TOKEN = $('input[name="_token"]').val();
            if (_type === "email") {
                var content = $('#messagecontent').val();
                var subject = $('#subject').val();
                send_email(checkedIDss, subject, content);
            }
            else if (_type === "state") {
                change_state(checkedIDss);
            }
            else
                alert('Please Choose ' + _type + '!')
        });
    </script>

@endsection
