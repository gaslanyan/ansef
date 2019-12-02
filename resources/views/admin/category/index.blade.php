@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                <div class="card" >
                    <div class="card-header">List of categories
                        @if(get_Cookie() == 'superadmin')
                            <a href="{{action('Admin\CategoryController@create')}}"
                               class="display float-lg-right btn-primary px-2 myButton">Add a category</a>
                        @endif
                    </div>
                    <div class="card-body card_body">
                        @include('partials.status_bar')

                        <div class="btn_add col-md-12">
                            <button type="button" disabled title="duplicate" id="duplicateCats"
                                    class="btn-link btn duplicate_cats offset-lg-3 col-lg-2 col-md-3 "><i
                                        class="fa fa-clone"></i>
                                Duplicate
                            </button>
                            <button type="button" disabled title="delete" id="deleteCats"
                                    class="btn-link btn delete_cats col-lg-2 col-md-3"><i
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
                                    <label for="cat" class="label">
                                        <input type="checkbox" class="form-control check_all"
                                               id="cat">
                                    </label>
                                </th>
                                <th width="100px">Abbreviation</th>
                                <th>Title</th>
                                <th>Parent</th>
                                <th style="width:100px">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($categories))
                                @foreach($categories as $category)
                                    <?php //echo "<pre>";var_dump($category['id']);die;?>
                                    <tr>
                                        <td data-order="data-order='[[ 1, &quot;asc&quot; ]]'"></td>
                                        <td><label for="category{{$category['id']}}" class="label">
                                                <input type="checkbox" class="form-control checkbox" name="id[]"
                                                       value="{{$category['id']}}"
                                                       id="cat{{$category['id']}}">
                                            </label>
                                        </td>

                                        <td data-order="{{$category['abbreviation']}}"
                                            data-search="{{$category['abbreviation']}}"
                                            class="abbreviation_field">
                                            <input type="text" class="form-control" name="abbreviation"
                                                   value="{{$category['abbreviation']}}" disabled>
                                        </td>
                                        <td data-order="{{$category['title']}}" data-search="{{$category['title']}}"
                                            class="title_field">
                                            <input type="text" class="form-control" name="title"
                                                   value="{{$category['title']}}" disabled>
                                        </td>
                                        @php $gsv = getSelectedValue($parents,$category['parent_id']);
                                        @endphp
                                        <td class="state_field" data-order="{{ $gsv}}" data-search="{{$gsv}}">
                                            @if(!empty($parents))
                                                <select class="form-control" name="parent_id" disabled>
                                                    <option value="0"></option>
                                                    @foreach($parents as $item)
                                                        <option class="text-capitalize"
                                                                value="{{$item->id}}" @if($item->id === $category['parent_id'])
                                                            {{'selected'}} @endif>{{$item->title}}</option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        </td>

                                        <td>
                                            <input type="hidden" class="id" value="{{$category['id']}}">
                                            <input type="hidden" class="url" value="/updateCategory">
                                            <button title="Edit"
                                                    class="edit btn-link"><i class="fa fa-pencil-alt"></i>
                                            </button>
                                            <button title="Save"
                                                    class="save editable btn-link"><i class="fa fa-save"></i>
                                            </button>
                                            <button title="Cancel"
                                                    class="cancel editable btn-link"><i class="fa fa-ban"></i>
                                            </button>

                                            <form action="{{action('Admin\CategoryController@destroy', $category['id'])}}"
                                                  method="post">
                                                @csrf
                                                <input name="_method" type="hidden" value="DELETE">
                                                <input name="_id" type="hidden" value="{{$category['id']}}">
                                                <button class="btn-link delete" type="button" data-title="category">
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
