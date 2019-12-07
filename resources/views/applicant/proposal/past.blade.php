@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                <div class="card" >
                    <div class="card-header">List of Past Proposals
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


                    <div class="card-body card_body" style="overflow:auto;">
                        @if(!empty($pastproposal))
                            <table class="table table-responsive-md table-sm table-bordered display" id="example"
                                   style="width:100%">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Proposal Name</th>
                                    <th>Proposal State</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($pastproposal as $pp)
                                    <tr>
                                        <td></td>
                                        <td data-order="{{$pp['title']}}" data-search="{{$pp['title']}}"
                                            class="email_field">
                                            <input type="text" class="form-control" name="email"
                                                   value="{{$pp['title']}}" disabled>
                                        </td>
                                        <td data-order="{{$pp['state']}}" data-search="{{$pp['state']}}"
                                            class="email_field">
                                            <select id="type" class="form-control" name="type" disabled>
                                                <?php $enum = getEnumValues('proposals', 'state');?>
                                                <option>Change State</option>
                                                @if(!empty($enum))
                                                    @foreach($enum as $item)
                                                        @if($item == $pp['state'])
                                                            <option class="text-capitalize" value="{{$item}}"
                                                                    selected>{{$item}}</option>
                                                        @else
                                                            <option class="text-capitalize"
                                                                    value="{{$item}}">{{$item}}</option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                        </td>
                                        <td>
                                            <input type="hidden" class="id" value="{{$pp['id']}}">
                                            <a href="{{action('Applicant\ProposalController@show', $pp['id'])}}"
                                               class="view" title="View"><i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{action('Applicant\ProposalController@generatePDF',$pp['id'])}}"
                                               title="Download"
                                               class="add_honors"><i class="fa fa-download"></i>
                                            </a>
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
                ],
                "scrollX": true
            });
            t.on('order.dt search.dt', function () {
                t.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        });

    </script>
@endsection
