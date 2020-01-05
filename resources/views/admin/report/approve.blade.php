@extends('layouts.master')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">List of PI reports

                </div>
                <div class="card-body" style="overflow:auto;">
                    @include('partials.status_bar')

                    @if(!empty($reports))
                    <table class=" table table-responsive-md table-sm table-bordered " id="example">
                        <thead>
                            <tr>
                                <th></th>
                                <th>ID</th>
                                <th>Description</th>
                                <th>Due Date</th>
                                <th>State</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($reports as $pr)
                            <tr>
                                <td></td>

                                <td>
                                    {{$pr->proposal['id'] ? getProposalTag($pr->proposal['id']) : ''}}
                                </td>
                                <td>
                                    {{$pr->description}}
                                </td>
                                <td>
                                    {{$pr->due_date}}
                                </td>
                                <td>

                                    <select class="form-control " name="approve" disabled>
                                        <option class="text-capitalize" value="0">{{'Not Approved'}}</option>
                                        <option class="text-capitalize" value="1" @if($pr->approved == 1)
                                            {{'selected'}} @endif>{{'Approved'}}
                                        </option>


                                    </select>
                                </td>

                                <td>
                                    <input type="hidden" class="id" value="{{$pr->id}}">
                                    <input type="hidden" class="url" value="/approve">
                                    <button title="Edit" class="edit btn-link">
                                        <i class="fa fa-pencil-alt"></i>
                                    </button>
                                    <button title="Save" class="save editable btn-link">
                                        <i class="fa fa-save"></i>
                                    </button>
                                    <button title="Cancel" class="cancel editable btn-link"><i class="fa fa-ban"></i>
                                    </button>
                                    <input type="hidden" class="id" name="applicant" value="{{$pr->id}}">

                                    <a href="{{action('Admin\ProposalController@show', $pr->proposal_id)}}" class="view"
                                        title="View"><i class="fa fa-eye"></i>
                                    </a>

                                </td>

                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                    @else
                    "cka";
                    @endif

                </div>

            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        var t = $('#example').DataTable({
            "dom": '<"top"flp>rt<"bottom"i><"clear">',
            "pagingType": "full_numbers",
            "columnDefs": [{
                "targets": [1],
                "searchable": false,
                "orderable": false,
                "visible": true
            }],
            "scrollX": true
        });
        t.on('order.dt search.dt', function () {
            t.column(0, {
                search: 'applied',
                order: 'applied'
            }).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();
    });

</script>

@endsection
