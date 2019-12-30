@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                 <div class="card" >
                    <div class="card-header">List of Proposals
                        <a href="{{action('Applicant\ProposalController@create')}}"
                           class="display float-lg-right btn-primary px-2">Create Proposal</a>
                      </div>
                    <div class="card-body card_body" style="overflow:auto;">
                        @include('partials.status_bar')


                        <table class="table table-responsive-md table-sm table-bordered display" id="example"
                               style="width:100%">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Title</th>
                                <th>Submission start date</th>
                                <th>Submission end date</th>
                                <th>Announcement date</th>
                                <th>State</th>
                                <th style="width:100px">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($coms))
                                @foreach($coms as $com)
                                    <tr>
                                        <td data-order="data-order='[[ 1, &quot;asc&quot; ]]'"></td>
                                        <td data-order="{{$com->title}}" data-search="{{$com->title}}"
                                            class="title_field">
                                            <input type="text" class="form-control" name="title"
                                                   value="{{$com->title}}" disabled>
                                        </td>
                                        <td data-order="{{$com->submission_start_date}}" class="ssd_field">
                                            <input type="text" class="form-control
                                             date datepicker" name="submission_start_date"
                                                   value="{{$com->submission_start_date}}" disabled>
                                        </td>
                                        <td data-order="{{$com->submission_end_date}}" class="sed_field">
                                            <input type="text" class="form-control
                                             date datepicker" name="submission_end_date"
                                                   value="{{$com->submission_start_date}}" disabled>
                                        </td>
                                        <td data-order="{{$com->announcement_date}}" class="ad_field">
                                            <input type="text" class="form-control
                                             date datepicker" name="announcement_date"
                                                   value="{{$com->announcement_date}}" disabled>
                                        </td>
                                        <td data-order="{{$com->state}}" class="state_field">
                                            <select class="form-control" name="state" disabled>
                                                <?php $enum = getEnumValues('competitions', 'state');?>
                                                <option value="0">Select state</option>
                                                @if(!empty($enum))
                                                    @foreach($enum as $item)
                                                        <option class="text-capitalize"
                                                                value="{{$item}}" @if($item === $com->state)
                                                            {{'selected'}} @endif>{{$item}}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
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
    <script>
        $(document).ready(function () {
            var t = $('#example').DataTable({
                "pagingType": "full_numbers",
                // "order": [[0, "asc"]],
                // "columnDefs": [
                //     {
                //         "targets": [5],
                //         "searchable": false
                //     }
                // ]
                columnDefs: [{
                    targets: [0],
                    orderData: [0, 1]
                }, {
                    targets: [1],
                    orderData: [1, 0]
                }]
            });
            t.on('order.dt search.dt', function () {
                t.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        });

    </script>

@endsection
{{--<form action="{{action('Admin\comController@destroy', $com['id'])}}"--}}
{{--method="post">--}}
{{--@csrf--}}
{{--<input name="_method" type="hidden" value="DELETE">--}}
{{--<button class="btn btn-danger" type="submit">Login</button>--}}
{{--</form>--}}
