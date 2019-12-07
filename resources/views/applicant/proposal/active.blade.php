@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                <div class="card" >
                    <div class="card-header">List of Current Proposals
                        <a href="{{action('Applicant\ProposalController@create')}}"
                           class="display float-lg-right btn-primary px-2 myButton">Add A New Proposal</a>
                    </div>
                    @include('partials.status_bar')

                    <div class="card-body card_body" style="overflow:auto;">
                        @if(!empty($activeproposals))
                            <table class="table table-responsive-md table-sm table-bordered display" id="example"
                                   style="width:100%">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Proposal Title</th>
                                    <th>Proposal State</th>
                                    {{--<th>Email</th>--}}
                                    <th colspan="2">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($activeproposals as $ap)
                                    <tr>
                                        <td></td>
                                        <td data-order="{{$ap['title']}}" data-search="{{$ap['title']}}"
                                            class="email_field">
                                            {{truncate($ap['title'],55)}}
                                        </td>
                                        <td data-order="{{$ap['state']}}" data-search="{{$ap['state']}}"
                                            class="email_field">

                                            <select id="type" class="form-control" name="type" disabled>
                                                <?php $enum = getEnumValues('proposals', 'state');?>
                                                <option>Change State</option>
                                                @if(!empty($enum))
                                                    @foreach($enum as $item)
                                                        @if($item == $ap['state'])
                                                            <option class="text-capitalize" value="{{$item}}" selected>{{$item}}</option>
                                                            @else
                                                            <option class="text-capitalize" value="{{$item}}">{{$item}}</option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>

                                        </td>
                                        <td>
                                            <input type="hidden" class="id" value="{{$ap['id']}}">
                                            <a href="{{action('Applicant\ProposalController@show', $ap['id'])}}" title="View">
                                                <span class="fa fa-eye myButton">View</span>
                                            </a>

                                            <a href="{{action('Applicant\ProposalController@edit', $ap['id'])}}"
                                               title="Edit"><span class="fa fa-edit myButton">Edit</span>
                                            </a>
                                            <input type="hidden" class="id" value="{{$ap['id']}}">


                                            <a href="{{action('Applicant\ProposalController@destroy', $ap['id'])}}"
                                               title="Delete" onclick="return confirm('Are you sure you want to delete the proposal?')"><span class="fa fa-trash myButton">Delete</span>
                                            </a><br/>

                                            <a href="{{action('Applicant\ProposalController@updatepersons', $ap['id'])}}">
                                                <span class="fas fa-user-friends myButton">Add/Remove Participants</span></a><br/>

                                                <a href="{{action('Applicant\BudgetCategoriesController@create', $ap['id'])}}">
                                                <span class="fas fa-file-invoice-dollar myButton">Budget</span></a>

                                            <a href="{{action('Applicant\FileUploadController@docfile', $ap['id'])}}"
                                               title="Delete">
                                               <?php if($ap['document'] == null){
                                                echo " <span class='fas fa-file-pdf myButton' style='color:#dd4b39 !important;'>Document</span>";
                                                }
                                                else{
                                                echo " <span class='fas fa-file-pdf myButton'>Document</span>";
                                                }?>
                                            </a>


                                            <a href="{{action('Applicant\ProposalController@check', $ap['id'])}}"><span class="fas fa-check-square myButton">Check</span></a>

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

        </script>
@endsection
