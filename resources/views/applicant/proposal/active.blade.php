@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                <div class="card" style="margin-top:20px;">
                    <div class="card-header">List of Current Proposals
                        <a href="{{action('Applicant\ProposalController@create')}}"
                           class="display float-lg-right btn-primary px-2">Add A New Proposal</a>
                    </div>
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
                        @if(!empty($activeproposal))
                            <table class="table table-responsive-md table-sm table-bordered display" id="example"
                                   style="width:100%">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Proposal Name</th>
                                    <th>Proposal State</th>
                                    {{--<th>Email</th>--}}
                                    <th colspan="2">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($activeproposal as $ap)
                                    <tr>
                                        <td></td>
                                        <td data-order="{{$ap['title']}}" data-search="{{$ap['title']}}"
                                            class="email_field">
                                            <input type="text" class="form-control" name="email"
                                                   value="{{$ap['title']}}" disabled>
                                        </td>
                                        <td data-order="{{$ap['state']}}" data-search="{{$ap['state']}}"
                                            class="email_field">
                                            {{--<input type="text" class="form-control" name="email"--}}
                                                   {{--value="{{$ap['state']}}" disabled>--}}

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
                                        {{--<td data-order="{{$email->email}}" data-search="{{$email->email}}"--}}
                                            {{--class="email_field">--}}
                                            {{--<input type="text" class="form-control" name="email"--}}
                                                   {{--value="{{$email->email}}" disabled>--}}
                                        {{--</td>--}}
                                        <td>
                                            <input type="hidden" class="id" value="{{$ap['id']}}">
                                            {{--<button title="Edit"--}}
                                            {{--class="edit btn-link"><i class="fa fa-pencil-alt"></i>--}}
                                            {{--</button>--}}
                                            {{--<button title="Cancel"--}}
                                            {{--class="cancel editable btn-link"><i class="fa fa-ban"></i>--}}
                                            {{--</button>--}}
                                            <a href="{{action('Applicant\ProposalController@show', $ap['id'])}}"
                                            class="view" title="View"><i class="fa fa-eye"></i>
                                            </a>

                                            {{--<a href="{{action('Applicant\EmailController@edit', $email->id)}}"--}}
                                                    {{--title="full_edit"--}}
                                                    {{--class="full_edit"><i class="fa fa-edit"></i>--}}
                                            {{--</a>--}}
                                            <a href="{{action('Applicant\ProposalController@edit', $ap['id'])}}"
                                               title="Edit"
                                               class=""><i class="fa fa-edit"></i>
                                            </a>
                                            <input type="hidden" class="id" value="{{$ap['id']}}">
                                            <!--<button title="Edit"-->
                                            <!--        class="edit btn-link"><i class="fa fa-pencil-alt"></i>-->
                                            <!--</button>-->
                                            <!--<button title="Save"-->
                                            <!--        class="save_prop editable btn-link"><i class="fa fa-save"></i>-->
                                            <!--</button>-->
                                            <!--<button title="Cancel"-->
                                            <!--        class="cancel editable btn-link"><i class="fa fa-ban"></i>-->
                                            <!--</button>-->
                                            {{-- <a href="{{action('Applicant\BudgetController@edit', $ap['id'])}}" title="Budget" class="add_budgets"> --}}
                                                <a><i class="fas fa-file-invoice-dollar"></i></a>
                                            {{-- </a> --}}

                                            <a href="{{action('Applicant\ProposalController@destroy', $ap['id'])}}"
                                               title="Delete"
                                               class="add_honors"><i class="fa fa-trash"></i>
                                            </a>

                                            <a href="{{action('Applicant\ProposalController@generatePDF',$ap['id'])}}"
                                               title="Download"
                                               class="add_honors"><i class="fa fa-download"></i>
                                            </a>
                                            {{-- <a href="{{action('Applicant\ProposalController@validate', $ap['id'])}}" title="Validate" class="validate"> --}}
                                                <a><i class="fas fa-check-square"></i></a>
                                            {{-- </a> --}}

                                            {{--<form action="{{action('Applicant\EmailController@destroy', $person['id'])}}" method="post">--}}
                                                {{--@csrf--}}
                                                {{--<input name="_method" type="hidden" value="DELETE">--}}
                                                {{--<button type="submit" class=" btn-link"><i class="fa fa-trash"></i></button>--}}
                                            {{--</form>--}}


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
