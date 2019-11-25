@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                <div class="card" >
                    <div class="card-header">Welcome to the ANSEF portal </div>
                    @if (\Session::has('success'))
                        <div class="alert alert-success">
                            <p>@php echo html_entity_decode(\Session::get('success'), ENT_HTML5) @endphp</p>
                        </div><br/>
                    @elseif (\Session::has('wrong'))
                        <div class="alert alert-success">
                            <p>{{ \Session::get('wrong') }}</p>
                        </div><br/>
                    @endif


                    <div class="card-body card_body">
                        @if(count($competitionlist)>0)
                            <p><b>Here's a list of competitions that you can currently apply for:</b></p>
                            <table class="table table-responsive-md table-sm table-bordered display" id="example"
                                   style="width:100%"  valign="middle">
                                <thead>
                                <tr>
                                    <th hidden></th>
                                    <th width="100px">Identifier</th>
                                    <th>Title</th>
                                    <th width="150px">Deadline</th>
                                    <th width="100px"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($competitionlist as $comp)
                                    <tr>
                                        <td hidden></td>
                                        <td data-order="{{$comp['title']}}" data-search="{{$comp['title']}}">
                                            {{$comp['title']}}
                                        </td>
                                        <td data-order="{{$comp['description']}}" data-search="{{$comp['description']}}">
                                            {{getTitleOfCompetition($comp['description'])}}
                                        </td>
                                        <td data-order="{{$comp['submission_end_date']}}" data-search="{{$comp['submission_end_date']}}">
                                            <b>{{$comp['submission_end_date']}}</b>
                                        </td>
                                        <td>
                                            <input type="reset" class="btn btn-primary" value ="Learn more" onClick="open_container(1);">
                                        </td>
        <!-- Modal form-->
        <div class="modal fade" id="myModal1" tabindex="-1" role="dialog"
                aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog ">
                <div class="modal-content" style="min-height:250px;min-width:320px;">
                    <div class="modal-header">
                        <h4 style="color:#666;"> Description for {{$comp['title']}}</h4>
                        <button type="button" class="close" data-dismiss="modal" style="width:50px;height:50px;">&times;</button>
                    </div>
                    <div class="modal-body" id="modal-bodyku">
                        {{$comp['description']}}
                    </div>
                </div>
            </div>
        </div>
        <!-- end of modal ------------------------------>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                                <h5>There are no competitions that you can currently apply for.</h5>
                        @endif
                    </div>

                     <div class="card-body card_body">
                        @if(!empty($upcomingcompetitions))
                            <p><b>Here's a list of upcoming competitions that you can apply for in the near future:</b></p>
                            <table class="table table-responsive-md table-sm table-bordered display" id="example"
                                   style="width:100%"  valign="middle">
                                <thead>
                                <tr>
                                    <th hidden></th>
                                    <th width="100px">Identifier</th>
                                    <th>Title</th>
                                    <th width="150px">Submissions start on</th>
                                    <th width="150px">Deadline</th>
                                    <th width="100px"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($upcomingcompetitions as $comp)
                                    <tr>
                                        <td hidden></td>
                                        <td data-order="{{$comp['title']}}" data-search="{{$comp['title']}}">
                                            {{$comp['title']}}
                                        </td>
                                        <td data-order="{{$comp['description']}}" data-search="{{$comp['description']}}">
                                            {{getTitleOfCompetition($comp['description'])}}
                                        </td>
                                        <td data-order="{{$comp['submission_start_date']}}" data-search="{{$comp['submission_start_date']}}">
                                            <b>{{$comp['submission_start_date']}}</b>
                                        </td>
                                        <td data-order="{{$comp['submission_end_date']}}" data-search="{{$comp['submission_end_date']}}">
                                            <b>{{$comp['submission_end_date']}}</b>
                                        </td>
                                        <td>
                                            <input type="reset" class="btn btn-primary" value ="Learn more" onClick="open_container(2);">
                                        </td>
        <!-- Modal form-->
        <div class="modal fade" id="myModal2" tabindex="-1" role="dialog"
                aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog ">
                <div class="modal-content" style="min-height:250px;min-width:320px;">
                    <div class="modal-header">
                        <h4 style="color:#666;"> Description for {{$comp['title']}}</h4>
                        <button type="button" class="close" data-dismiss="modal" style="width:50px;height:50px;">&times;</button>
                    </div>
                    <div class="modal-body" id="modal-bodyku">
                        {{$comp['description']}}
                    </div>
                </div>
            </div>
        </div>
        <!-- end of modal ------------------------------>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            var t = $('#example').DataTable({
                //"pagingType": "full_numbers",
                "paging": false,
                "columnDefs": [
                    {
                        "targets": [3],
                        "searchable": false
                    }, {
                        "targets": [3],
                        "searchable": false
                    }
                ]
            });
            t.on('order.dt search.dt', function () {
                t.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        });


        function open_container(n) {
            var size = 'large'; //small,standart,large document.getElementById('mysize').value;
            var content = '';//<form role="form"><div class="form-group"><label for="exampleInputEmail1">Email address</label><input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email"></div><div class="form-group"><label for="exampleInputPassword1">Password</label><input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password"></div><div class="form-group"><label for="exampleInputFile">File input</label><input type="file" id="exampleInputFile"><p class="help-block">Example block-level help text here.</p></div><div class="checkbox"><label><input type="checkbox"> Check me out</label></div><button type="submit" class="btn btn-default">Submit</button></form>';
            var title = '';
            var footer = '';//'<button type="button" class="btn btn-default" data-dismiss="modal">Close</button><button type="button" class="btn btn-primary">Save changes</button>';
            jQuery.noConflict();
            jQuery('#myModal' + n).modal('show');
        }


    </script>
    @endsection
