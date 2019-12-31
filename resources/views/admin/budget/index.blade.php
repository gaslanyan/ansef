@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card" >

                    <div class="card-header">List of budget categories
                        @if(get_role_cookie() == 'superadmin')
                            <a href="{{action('Admin\BudgetCategoryController@create')}}"
                               class="display float-lg-right btn-primary px-2 myButton"><i class="fas fa-plus"></i>&nbsp;Add a budget category</a>
                        @endif
                    </div>
                    <div class="card-body" style="overflow:auto;">
                        @include('partials.status_bar')


                        <div class="btn_add col-md-12">
                            <button type="button" disabled title="delete" id="deleteBudgets"
                                    class="btn-link btn delete_budgets offset-lg-6 col-lg-2 col-md-3"><i
                                        class="fa fa-trash-alt"></i>
                                Delete
                            </button>
                        </div>
                        <table class="table table-responsive-md table-sm table-bordered display compact" id="example"
                               style="width:100%">
                            <thead>
                            <tr>
                                <th></th>
                                <th>
                                    <label for="budget" class="label">
                                        <input type="checkbox" class="form-control check_all"
                                               id="budget">
                                    </label>
                                </th>
                                <th>Name</th>
                                <th>Min $</th>
                                <th>Max $</th>
                                <th>Weight</th>
                                <th>Competition</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($budgets))
                                @foreach($budgets as $budget)
                                    <tr>
                                        <td data-order="data-order='[[ 1, &quot;asc&quot; ]]'"></td>
                                        <td><label for="budget{{$budget->id}}" class="label">
                                                <input type="checkbox" class="form-control checkbox" name="id[]"
                                                       value="{{$budget->id}}"
                                                       id="budget{{$budget->id}}">
                                            </label></td>
                                        <td data-order="{{$budget->name}}" data-search="{{$budget->name}}"
                                            class="name_field">
                                            <input type="text" class="form-control" name="name"
                                                   value="{{$budget->name}}" disabled>
                                        </td>
                                        <td data-order="{{$budget->min}}" data-search="{{$budget->min}}"
                                            class="min_field">
                                            <input type="number" class="form-control" name="min"
                                                   value="{{$budget->min}}" disabled>
                                        </td>
                                        <td data-order="{{$budget->max}}" data-search="{{$budget->max}}"
                                            class="max_field">
                                            <input type="number" class="form-control" name="max"
                                                   value="{{$budget->max}}" disabled>
                                        </td>
                                        <td data-order="{{$budget->weight}}" data-search="{{$budget->weight}}"
                                            class="weight_field">
                                            <input type="text" class="form-control" name="weight"
                                                   value="{{$budget->weight}}" disabled>
                                        </td>
                                        @php //@TODO write the function which get selected enum name for ordering & searching
                                    $gsv = getSelectedValueByKey($competition,$budget->competition_id);
                                        @endphp
                                        <td data-order="{{$gsv}}" data-search="{{$gsv}}"
                                            class="category_field">
                                            <select class="form-control cat" name="competition"
                                                    id="competition" disabled>
                                                <option value="0">Select Competition</option>
                                                <?php if(!empty($competition)):?>
                                                <?php foreach($competition as $key=>$item):?>

                                                <option class="text-capitalize"
                                                        @php if ($key == $budget->competition_id)
                                                        echo "selected";
                                                        @endphp
                                                        value="{{$key}}">{{$item}}</option>
                                                <?php endforeach;?>
                                                <?php endif;?>
                                            </select>
                                        </td>
                                        <td><a href="{{action('Admin\BudgetCategoryController@edit', $budget['id'])}}"
                                               class=""><i class="fa fa-pencil-alt"></i></a>
                                            <form action="{{action('Admin\BudgetCategoryController@destroy', $budget['id'])}}"
                                                  method="post">
                                                @csrf
                                                <input name="_method" type="hidden" value="DELETE">
                                                <input name="_id" type="hidden" value="{{$budget['id']}}">
                                                <button class="btn-link delete" type="button"
                                                        data-title="budget">
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
                "dom": '<"top"flp>rt<"bottom"i><"clear">',
                "pagingType": "full_numbers",
                "columnDefs": [
                    {
                        "targets": [1],
                        // "searchable": false,
                        // "orderable": false,
                        "visible": true
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
