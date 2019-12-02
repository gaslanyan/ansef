@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" >
                    <div class="card-header">List of competitions
                        @if(get_Cookie() === "superadmin")
                            <a href="{{action('Admin\CompetitionController@create')}}"
                               class="display float-lg-right btn-primary px-2 myButton">Add a competition</a>
                        @endif
                    </div>
                    <div class="card-body card_body">
                        @include('partials.status_bar')

                        @if (Session::has('delete'))
                            <div class="alert alert-info">
                                <p>{{ Session::get('delete') }}</p>
                            </div>
                        @endif
                        <table class="table table-responsive-md table-sm table-bordered display compact" id="example"
                               style="width:100%">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Competition</th>
                                <th>Categories</th>
                                <th>Announced</th>
                                <th>Open date</th>
                                <th>Close date</th>
                                <th>Project date</th>
                                <th>First report</th>
                                <th>Second report</th>
                                <th>Curr. State</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($coms))
                                @foreach($coms as $com)
                                    <tr>
                                        <td></td>
                                        <td data-order="@if(!empty($com->title)){{$com->title}} @endif"
                                            data-search="@if(!empty($com->title)){{$com->title}}@endif"
                                            class="title_field">
                                            <input type="text" class="form-control" name="title"
                                                   value="@if(!empty($com->title)){{$com->title}}@endif" disabled>
                                        </td>
                                        <td data-order="@if(!empty($com->categories)){{$com->categories}}@endif"
                                            data-search="@if(!empty($com->categories)){{$com->categories}}@endif"
                                            class="title_field">
                                            @if(!empty($com->categories))
                                                @php
                                                    $c = json_decode($com->categories,true);
                                                @endphp
                                            @endif
                                            @if(!empty($categories))
                                                @foreach($categories as $cat)
                                                    <span>{{$cat->abbreviation}}</span>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td data-order="@if(!empty($com->announcement_date)){{$com->announcement_date}}@endif"
                                            data-search="@if(!empty($com->announcement_date)){{$com->announcement_date}}@endif"
                                            class="ann_field">
                                            <input type="text" class="form-control
                        date datepicker" name="announcement_date"
                                                   value="@if(!empty($com->announcement_date)){{$com->announcement_date}}@endif"
                                                   disabled>
                                        </td>
                                        <td data-order="@if(!empty($com->submission_start_date)){{$com->submission_start_date}}@endif"
                                            data-search="@if(!empty($com->submission_start_date)){{$com->submission_start_date}}@endif"
                                            class="ssd_field">
                                            <input type="text" class="form-control
                        date datepicker" name="submission_start_date"
                                                   value="@if(!empty($com->submission_start_date)){{$com->submission_start_date}}@endif"
                                                   disabled>
                                        </td>
                                        <td data-order="@if(!empty($com->submission_end_date)){{$com->submission_end_date}}@endif"
                                            data-search="@if(!empty($com->submission_end_date)){{$com->submission_end_date}}@endif"
                                            class="sed_field">
                                            <input type="text" class="form-control
                        date datepicker" name="submission_end_date"
                                                   value="@if(!empty($com->submission_end_date)){{$com->submission_end_date}}@endif"
                                                   disabled>
                                        </td>
                                        <td data-order="@if(!empty($com->project_start_date)){{$com->project_start_date}}@endif"
                                            data-search="@if(!empty($com->project_start_date)){{$com->project_start_date}}@endif"
                                            class="project_field">
                                            <input type="text" class="form-control
                        date datepicker" name="project_start_date"
                                                   value="@if(!empty($com->project_start_date)){{$com->project_start_date}}@endif"
                                                   disabled>
                                        </td>
                                        <td data-order="@if(!empty($com->first_report)){{$com->first_report}}@endif"
                                            data-search="@if(!empty($com->first_report)){{$com->first_report}}@endif"
                                            class="first_report">
                                            <input type="text" class="form-control
                        date datepicker" name="first_report"
                                                   value="@if(!empty($com->first_report)){{$com->first_report}}@endif"
                                                   disabled>
                                        </td>
                                        <td data-order="@if(!empty($com->second_report)){{$com->second_report}}@endif"
                                            data-search="@if(!empty($com->second_report)){{$com->second_report}}@endif"
                                            class="second_report">
                                            <input type="text" class="form-control
                        date datepicker" name="second_report"
                                                   value="@if(!empty($com->second_report)){{$com->second_report}}@endif"
                                                   disabled>
                                        </td>

                                        <td data-order="@if(!empty($com->state)){{$com->state}}@endif"
                                            data-search="@if(!empty($com->state)){{$com->state}}@endif"
                                            class="state_field">
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
                                        <td>
                                            <input type="hidden" class="id" value="{{$com->id}}">
                                            <input type="hidden" class="url" value="/updateCom">
                                            <button title="Edit"
                                                    class="edit btn-link"><i class="fa fa-pencil-alt"></i>
                                            </button>
                                            <button title="Save"
                                                    class="save editable btn-link"><i class="fa fa-save"></i>
                                            </button>
                                            <button title="Cancel"
                                                    class="cancel editable btn-link"><i class="fa fa-ban"></i>
                                            </button>
                                            <a href="{{action('Admin\CompetitionController@show', $com->id)}}"
                                               class="view" title="View"><i class="fa fa-eye"></i>
                                            </a>

                                            <form action="{{action('Admin\CompetitionController@destroy', $com->id)}}"
                                                  method="post">
                                                @csrf
                                                <input name="_method" type="hidden" value="DELETE">
                                                <input name="_id" type="hidden" value="{{$com->id}}">
                                                <button class="btn-link delete" type="button"
                                                        data-title="competition">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
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
                "scrollX": true,
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
