@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card" >
                    <div class="card-header">Create Budget category
                        <a href = "{{ action('Admin\BudgetCategoryController@index') }}" class="display float-lg-right btn-box-tool">Go Back</a>
                        <br>
                        <i class="fas fa-question-circle text-blue all">{{Lang::get('messages.required_all')}}</i>
                    </div>

                    <div class="card-body card_body" style="overflow:auto;">
                        @include('partials.status_bar')


                        <form method="post" action="{{action('Admin\BudgetCategoryController@store')}}"
                              class="row">
                            @csrf
                            <div class="form-group col-lg-6">
                                <label for="name">Budget Name *:</label>
                                <input type="text" class="form-control"
                                       id="name" name="name" value="{{old('name')}}">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="competition">Competition Name *:</label>
                                <select class="form-control cat" name="competition_id"
                                        id="competition">
                                    <option >Select Competition</option>
                                    <?php if(!empty($competition)):?>
                                    @php
                                        $compindex = old('competition_id');
                                    @endphp
                                    <?php foreach($competition as $key=>$item):?>
                                    <option class="text-capitalize" value="{{$key}}" {{$compindex == $key ? 'selected' : ''}}>
                                        {{$item}}</option>
                                    <?php endforeach;?>
                                    <?php endif;?>
                                </select>
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="min">Budget Min value *:</label>
                                <input type="number" class="form-control"
                                       id="min" name="min" value="{{old('min')}}">
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="max">Budget Max value *:</label>
                                <input type="number" class="form-control"
                                       id="max" name="max" value="{{old('max')}}">
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="weight">Budget Weight value *:</label>
                                <input type="number" class="form-control"
                                       id="weight" name="weight" value="{{old('weight')}}">
                            </div>
                            <div class="form-group col-lg-12">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <button type="button" onclick="open_container()" class="btn btn-primary">Copy from another budget category
                                    Category
                                </button>
                                <a href = "{{ action('Admin\BudgetCategoryController@index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal form-->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h2></h2>
                    <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span
                                class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel"></h4>
                </div>
                <div class="modal-body" id="modal-bodyku">
                    @if(!empty($budgets))
                        <table class="table table-responsive-md table-sm table-bordered display" id="example">
                            <thead>
                            <tr>
                                <th></th>
                                <th>#</th>
                                <th>Competition Name</th>
                                <th>Budget Categories Name</th>
                                <th>Min $</th>
                                <th>Max $</th>
                                <th>Weight</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($budgets as $i=>$r)
                                <tr>
                                    <td></td>
                                    <td>

                                        <label for="choose_id{{$i}}"
                                               class="label">
                                            <input type="radio" name="id" id="choose_id{{$i}}"
                                                   value="{{$r->id}}">
                                        </label>
                                    </td>
                                    <td>
                                        {{$r->competition->title}}
                                    </td>
                                    <td>
                                        {{$r->name}}
                                    </td>
                                    <td>
                                        {{$r->min}}
                                    </td>
                                    <td>
                                        {{$r->max}}
                                    </td>
                                    <td>
                                        {{$r->weight." "}}
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
                <div class="modal-footer" id="modal-footerq">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">Close
                    </button>
                    <button type="button" class="btn btn-primary"
                            data-dismiss="modal" id="choose">Choose
                    </button>
                </div>

            </div>
        </div>
    </div>
    <!-- end of modal ------------------------------>

    <script>
        $(document).ready(function () {
            var groupColumn = 2;
            var t = $('#example').DataTable({
                "pagingType": "full_numbers",

                "columnDefs": [
                    {
                        "visible": false,
                        "targets": groupColumn,
                        // "targets": [1],
                        // "searchable": false,
                        // "orderable": false,
                        // "visible": true
                    }
                ],
                // "scrollX": true
                "order": [[ groupColumn, 'asc' ]],
                // "displayLength": 25,
                "drawCallback": function ( settings ) {
                    var api = this.api();
                    var rows = api.rows( {page:'current'} ).nodes();
                    var last=null;

                    api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                        if ( last !== group ) {
                            $(rows).eq( i ).before(
                                '<tr class="group"><td colspan="6">'+group+'</td></tr>'
                            );

                            last = group;
                        }
                    } );
                }
            } );

            // Order by the grouping
            $('#example tbody').on( 'click', 'tr.group', function () {
                var currentOrder = t.order()[0];
                if ( currentOrder[0] === groupColumn && currentOrder[1] === 'asc' ) {
                    t.order( [ groupColumn, 'desc' ] ).draw();
                }
                else {
                    t.order( [ groupColumn, 'asc' ] ).draw();
                }
            } );
            t.on('order.dt search.dt', function () {
                t.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        });

        function open_container() {
            var size = 'small',
                content = '',
                title = 'Choose a budget category',
                footer = '';
            jQuery.noConflict();
            setModalBox(title, size);
            jQuery('#myModal').modal('show');

        }

        function setModalBox(title, content, footer, $size) {
            jQuery('#myModal').find('.modal-header h2').text(title);

            if ($size === 'small') {
                jQuery('#myModal').attr('class', 'modal fade bs-example-modal-sm')
                    .attr('aria-labelledby', 'mySmallModalLabel');
                jQuery('.modal-dialog').attr('class', 'modal-dialog modal-sm');
            }
        }

        jQuery('#choose').on('click', function () {
            var checkedIDs = "";
            // checkedIDss = [];
            jQuery(".modal-body input:radio:checked").each(function () {
                checkedIDs = $(this).val();
            });
            // jQuery(".radio:checked").each(function () {
            //     checkedIDss.push($(this).val());
            // });

            var CSRF_TOKEN = $('input[name="_token"]').val();
            if (checkedIDs.length > 0) {
                jQuery.ajax({
                    url: '/admin/copyItems',
                    type: 'POST',
                    context: {element: $(this)},
                    data: {
                        _token: CSRF_TOKEN,
                        id: checkedIDs,
                        table: 'budget_categories'
                    },
                    dataType: 'JSON',
                    success: function (data) {
                        console.log(data)
                        $('#name').val(data.name);
                        $('#min').val(data.min);
                        $('#max').val(data.max);
                        $('#weight').val(data.weight);
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });
            } else
                alert('Please Choose Budget Categories!')
        });

    </script>
@endsection
