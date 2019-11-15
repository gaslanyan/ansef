@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" style="margin-top:20px;">
                    <div class="card-header">List of score types
                        @if(get_Cookie() === "superadmin")
                            <a href="{{action('Admin\ScoreTypeController@create')}}"
                               class="display float-lg-right btn-primary px-2">Add a score type</a>
                        @endif
                    </div>
                    <div class="card-body card_body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (\Session::has('success'))
                            <div class="alert alert-success">
                                <p>@php echo html_entity_decode(\Session::get('success'), ENT_HTML5) @endphp</p>
                            </div><br/>
                        @endif
                        @if (\Session::has('error'))
                            <div class="alert alert-danger">
                                <p>@php echo html_entity_decode(\Session::get('error'), ENT_HTML5) @endphp</p>
                            </div>
                        @endif
                        @if (\Session::has('delete'))
                            <div class="alert alert-info">
                                <p>@php echo html_entity_decode(\Session::get('delete'), ENT_HTML5) @endphp</p>
                            </div>
                        @endif
                        <div class="btn_add col-md-12">

                            <button type="button" disabled title="delete" id="deleteScores"
                                    class="btn-link btn delete_cats offset-lg-6 col-lg-2 col-md-3"><i
                                        class="fa fa-trash-alt"></i>
                                {{ csrf_field() }}
                                Delete
                            </button>
                        </div>
                        <table class="table table-responsive-md table-sm table-bordered display" id="example"
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
                                <th>Name</th>
                                <th>Description</th>
                                <th>Min</th>
                                <th>Max</th>
                                <th>Weight</th>
                                <th>Competition</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($scoreTypes as $scoreType)
                                <tr>
                                    <td data-order="data-order='[[ 1, &quot;asc&quot; ]]'"></td>
                                    <td><label for="category{{$scoreType->id}}" class="label">
                                            <input type="checkbox" class="form-control checkbox" name="id[]"
                                                   value="{{$scoreType->id}}"
                                                   id="cat{{$scoreType->id}}">
                                        </label>
                                    </td>
                                    <td data-order="{{$scoreType->name}}" data-search="{{$scoreType->name}}"
                                        class="name_field">
                                        <input type="text" class="form-control" name="name"
                                               value="{{$scoreType->name}}" disabled>
                                    </td>
                                    <td data-order="{{$scoreType->description}}"
                                        data-search="{{$scoreType->description}}"
                                        class="name_field">
                                        {{$scoreType->description}}
                                    </td>
                                    <td data-order="{{$scoreType->min}}" data-search="{{$scoreType->min}}"
                                        class="min_field">
                                        <input type="number" class="form-control" name="min"
                                               value="{{$scoreType->min}}" disabled>
                                    </td>
                                    <td data-order="{{$scoreType->max}}" data-search="{{$scoreType->max}}"
                                        class="max_field">
                                        <input type="number" class="form-control" name="max"
                                               value="{{$scoreType->max}}" disabled>
                                    </td>
                                    <td data-order="{{$scoreType->weight}}" data-search="{{$scoreType->weight}}"
                                        class="weight_field">
                                        <input type="text" class="form-control" name="weight"
                                               value="{{$scoreType->weight}}" disabled>
                                    </td>
                                    @php //@TODO write the function which get selected enum name for ordering & searching
               $gsv = getSelectedValueByKey($competition,$scoreType->competition_id);
                                    @endphp
                                    <td data-order="{{$gsv}}" data-search="{{$gsv}}"
                                        class="category_field">
                                        <select class="form-control cat" name="competition"
                                                id="competition" disabled>
                                            <option value="0">Select Competition</option>
                                            <?php if(!empty($competition)):?>
                                            <?php foreach($competition as $key=>$item):?>

                                            <option class="text-capitalize"
                                                    <?php if ($key == $scoreType->competition_id):
                                                        echo "selected"; endif?>
                                                    value="{{$key}}">{{$item}}</option>
                                            <?php endforeach;?>
                                            <?php endif;?>
                                        </select>
                                    </td>
                                    <td>
                                        <a href="{{action('Admin\ScoreTypeController@edit', $scoreType['id'])}}"
                                           class=""><i class="fa fa-pencil-alt"></i></a>
                                        <form action="{{action('Admin\ScoreTypeController@destroy', $scoreType['id'])}}"
                                              method="post">
                                            @csrf
                                            <input name="_method" type="hidden" value="DELETE">
                                            <input name="_id" type="hidden" value="{{$scoreType['id']}}">
                                            <button class="btn-link delete" type="button"
                                                    data-title="{{'score'}}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
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
