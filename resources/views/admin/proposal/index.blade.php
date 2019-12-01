@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" >
                    <div class="card-header">List of proposals for competition :
                        <select name="competition" id="competition">
                            @foreach($competitions as $c)
                                <option value="{{$c['id']}}" {{$c['id']==$cid ? 'selected' : ''}}>{{$c['title']}}</option>
                            @endforeach
                                <option value="-1" {{-1==$cid ? 'selected' : ''}}>All</option>
                        </select>
                    </div>
                    <div class="card-body card_body">
                        @include('partials.status_bar')

                        <div class="btn_add col-md-12 justify-content-end">
                            @if(get_Cookie() === "superadmin")
                                <button type="button" disabled
                                        title="change state" onclick="open_container('state');"
                                        class="btn-link btn email col-lg-2 col-md-3 "><i
                                            class="fa fa-comments"></i>
                                    Change state
                                </button>

                                <button type="button" disabled
                                        title="send email" onclick="open_container('email');"
                                        class="btn-link btn email col-lg-2 col-md-3 "><i
                                            class="fa fa-envelope-open"></i>
                                    Send Email
                                </button>
                                <button type="button" disabled title="delete" id="deleteProposal"
                                        class="btn-link btn delete_proposals col-lg-2 col-md-3"><i
                                            class="fa fa-trash-alt"></i>
                                    Delete
                                </button>
                                <button type="button" disabled
                                        title="add admin" onclick="open_container('admin');"
                                        class="btn-link btn admin  col-lg-2 col-md-3 "><i
                                            class="fa fa-user-graduate"></i>
                                    Add Admin
                                </button>

                            @endif
                            @if(get_Cookie() === "superadmin" ||  get_Cookie() === "admin" )
                                <button type="button" disabled
                                        title="add referee" onclick="open_container('referee');"
                                        class="btn-link btn referee col-lg-2  col-md-3 align-self-end "><i
                                            class="fa fa-user-graduate"></i>
                                    Add Referee
                                </button>
                            @endif
                        </div>
                        <table class="table table-responsive-md table-sm table-bordered display compact" id="example">
                            <thead>
                            <tr>
                                <th></th>
                                <th>
                                    <label for="proposal" class="label">
                                        <input type="checkbox" class="form-control check_all"
                                               id="proposal">
                                    </label>
                                </th>
                                <!--<th>Time</th>-->
                                <th width="100px">ID</th>
                                <th>Proposal Title</th>
                                <th>Proposal PI</th>
                                <th>Referee</th>
                                <th>Admin</th>
                                <th width="100px">State</th>
                                <th class="action long">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($proposals))
                                @foreach($proposals as $pr)
                                    <tr>
                                        <td></td>
                                        <td><label for="proposal{{$pr['id']}}" class="label">
                                                <input type="checkbox" class="form-control checkbox" name="id[]"
                                                       value="{{$pr['id']}}"
                                                       id="proposal{{$pr['id']}}">
                                            </label></td>
                                        <td>
                                            {{$pr['tag']}}
                                        </td>
                                        <td class="title_field">
                                            {{$pr['title']}}
                                        </td>

                                        <td>
                                        </td>
                                        <td>
                                        </td>
                                        <td>
                                        </td>
                                        <td data-order="{{$pr['state']}}" class="state_field">
                                            {{$pr['state']}}
                                        </td>
                                        <td>
                                            <input type="hidden" class="id" value="{{$pr['id']}}">
                                            {{-- <input type="hidden" class="url" value="/updateProposal">
                                            <button title="Edit"
                                                    class="edit btn-link">
                                                <i class="fa fa-pencil-alt"></i>
                                            </button>
                                            <button title="Save"
                                                    class="save editable btn-link">
                                                <i class="fa fa-save"></i>
                                            </button>
                                            <button title="Cancel"
                                                    class="cancel editable btn-link"><i class="fa fa-ban"></i>
                                            </button> --}}

                                            <a href="{{action('Admin\ProposalController@show', $pr['id'])}}"
                                               class="view" title="View"><i class="fa fa-eye"></i>
                                            </a>
                                            {{-- <form action="{{action('Admin\ProposalController@destroy', $pr['id'])}}"
                                                  method="post">
                                                @csrf
                                                <input name="_method" type="hidden" value="DELETE">
                                                <button class="btn-link delete" type="button"><i
                                                            class="fa fa-trash"></i></button>
                                            </form> --}}
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
    <!-- Modal form-->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h2></h2>
                    <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span
                                class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel"></h4>
                </div>
                <div class="modal-body" id="modal-bodyku">
                    <div id="referee">

                        @if(!empty($referees))
                            @foreach($referees as $i=>$r)
                                <div class="row">
                                    <div class="form-group col-lg-1">
                                        <label for="choose_person_name{{$i}}"
                                               class="label">
                                            <input type="checkbox" name="choose_person[]" id="choose_person_name{{$i}}"
                                                   value="{{$r['id']}}"/>
                                        </label>


                                    </div>
                                    <div class="form-group col-lg-5">
                                        {{$r['first_name']." ".$r['last_name']}} ({{$r->user->email}})
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
                                        <label for="choose_person_name{{$i}}"
                                               class="label">
                                            <input type="checkbox" name="choose_person[]" id="choose_person_name{{$i}}"
                                                   value="{{$a['id']}}"/>
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
                        @if(!empty($messages))

                            <div class="row">
                                <div class="form-group col-lg-1">
                                    <label for="choose_person_email"
                                           class="label">
                                        <select name="message" id="choose_person_email">
                                            <option>Select tamplate</option>
                                            @foreach($messages as $m)
                                                <option value="{{$m->id}}">{{$m->text}}</option>
                                            @endforeach
                                        </select>
                                    </label>

                                </div>
                            </div>
                        @endif
                    </div>
                    <div id="state">


                        <div class="row">
                            <div class="form-group col-lg-10">
                                <label for="change_proposal_state"
                                       class="label">
                                    <select class="form-control col-12" name="change_proposal_state">
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
                        <button type="button" class="btn btn-default"
                                data-dismiss="modal">Close
                        </button>
                        <button type="button" class="btn btn-primary"
                                data-dismiss="modal" id="choose">Choose
                        </button>
                    </div>
                    <input type="hidden" class="form-control form-check-inline aaa"
                           name="hidden_choose_person[]" value="" id="aaa">
                    {{--</form>--}}
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- end of modal ------------------------------>

    <script>
        $(document).ready(function () {
            var t = $('#example').DataTable({
                "pagingType": "full_numbers",
                "columnDefs": [
                    {
                        "targets": [1],
                        "searchable": false,
                        "orderable": false,
                        "visible": true
                    }
                ],
                "scrollX": true,
                "scrollY": 450,
                "deferRender": true,
                "scrollCollapse": true,
                "scroller": true,
                "colReorder": true,
                "fixedColumns":   { "leftColumns": 3 },
                "processing": true,
                "language": {
                    "loadingRecords": '&nbsp;',
                    "processing": 'Loading...'
                },
                "dom": 'Bfrtip',
                "buttons": [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
            t.on('order.dt search.dt', function () {
                t.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();

            $('#competition').change(function() {
                // alert("Hello " + $(this).val());
                var url = '{!! route("proposal_list", ":id"); !!}';
                url = url.replace(':id', $(this).val());
                document.location.href=url;
            });
        });
        var _type = '';

        function open_container(type) {
            var size = 'small',
                content = '',
                title = 'Choose a ' + type,
                footer = '';
            _type = type;
            jQuery.noConflict();
            setModalBox(title, size);
            if ($('.checkbox:checked').length > 0)
                jQuery('#myModal').modal('show');
            else
                alert('Please Choose Proposal!')
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
            jQuery(".checkbox:checked").each(function () {
                checkedIDss.push($(this).val());
            });
            var CSRF_TOKEN = $('input[name="_token"]').val();
            if (_type === "email")
                send_email(checkedIDss);
            else if (checkedIDss.length > 0 && checkedIDs.length > 0) {
                jQuery.ajax({
                    url: '/addUsers',
                    type: 'POST',
                    context: {element: $(this)},
                    data: {
                        _token: CSRF_TOKEN,
                        p_ids: JSON.stringify(checkedIDss),
                        u_ids: JSON.stringify(checkedIDs),
                        type: _type
                    },
                    dataType: 'JSON',
                    success: function (data) {
                        console.log(data);
                        location.reload();
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });
            }
            else if (_type === "state") {
                change_state(checkedIDss);
            }
            else
                alert('Please Choose ' + _type + '!')
        });

    </script>

@endsection
