@extends('layouts.master')

@section('content')
<div class="container">

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                @php
                if (Cookie::get('cid') !== null)
                $cid = Cookie::get('cid');
                else {
                $cid = empty(\App\Models\Competition::latest('created_at')->first()) ? -1 :
                \App\Models\Competition::latest('created_at')->first()->id;
                }
                @endphp
                <div class="card-header">Create a ranking rule
                </div>

                <div class="card-body" style="overflow:auto;">
                    <i class="fas fa-question-circle text-blue all"> </i>&nbsp;<i
                        class="text-blue">{{Lang::get('messages.required_all')}}</i>
                    @include('partials.status_bar')


                    <form method="post" action="{{ action('Admin\RankingRuleController@store') }}" class="row">
                        @csrf
                        <div class="form-group col-lg-12">
                            <label for="sql">SQL:</label>
                            <textarea rows="10" class="form-control col-lg-12" id="sql" name="sql">
                                    @if(!empty(old('sql')))
                                        {{old('sql')}}
                                    @else

{
    "name": "Untitled",
    "pi_age": [0, 100],
    "pi_sex": [0.0, 1.0],
    "pi_publications": [0.0, 1000.0],
    "pi_publications_dom": [0.0, 1000.0],
    "pi_publications_ansef": [0.0, 1000.0],
    "pi_publications_ansef_dom": [0.0, 1000.0],
    "participants_sex": [0.0, 1.0],
    "participants": [0,10],
    "avg_part_age": [0.0, 100.0],
    "junior_participants": [0, 10],
    "part_publications": [0.0, 10000.0],
    "part_publications_ansef": [0.0, 10000.0],
    "part_publications_dom": [0.0, 10000.0],
    "part_publications_ansef_dom": [0.0, 10000.0],
    "category": [],
    "budget": [0.0, 5000.0],
    "pi_salary": [0.0, 2000.0],
    "collab_salary": [0.0, 2000.0],
    "avg_salary": [0.0, 2000.0],
    "salary_dev": [0.0, 100.0],
    "travel": [0.0, 2000.0],
    "equipment": [0.0, 2000.0],
    "subscore": [0.0, 7.0],
    "overall_score": [0.0, 7.0]
}
                                    @endif
                                </textarea>
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="value">Value:</label>
                            <input type="number" class="form-control" id="value" name="value" value="{{old('value')}}">
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="competition">Competition Name:</label>
                            <select class="form-control" name="competition_id" id="competition">
                                <option value="">Select Competition</option>
                                <?php if(!empty($competition)):?>
                                <?php foreach($competition as $key=>$item):?>
                                <option class="text-capitalize" value="{{$key}}" @if($key==old('competition_id') ||
                                    $key==$cid){{'selected'}} @endif>
                                    {{$key." - ".$item}}</option>
                                <?php endforeach;?>
                                <?php endif;?>
                            </select>
                        </div>
                        <div class="form-group col-lg-12">
                            <label for="competition">Owner (admin):</label>
                            <select class="form-control" name="user_id" id="competition">
                                <option value="">Select admin</option>
                                <?php
                                    if(!empty($users)):?>
                                <?php foreach($users as $item):?>
                                <option value="{{$item->user->id}}" @if($item->user->id == old('user_id')){{'selected'}}
                                    @endif>
                                    {{$item->id." - ".$item->first_name." ".$item->last_name." (".$item->user->email.")"}}
                                </option>
                                <?php endforeach;?>
                                <?php endif;?>
                            </select>
                        </div>
                        <div class="form-group col-lg-12">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="button" onclick="open_container()" class="btn btn-primary">Copy from another
                                ranking
                                rule
                            </button>
                            <a href="{{URL::previous()}}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal form-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h3></h3>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                        class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body" id="modal-bodyku">
                @if(!empty($rr) && count($rr)>0)
                <table width="100%" class="table table-responsive-md table-sm table-bordered display" id="example">
                    <thead>
                        <tr>
                            <th></th>
                            <th>#</th>
                            <th>Competition Name</th>
                            <th>Sql</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rr as $i=>$r)
                        <tr>
                            <td></td>
                            <td>
                                <label for="choose_id{{$i}}" class="label">
                                    <input type="radio" name="id" id="choose_id{{$i}}" value="{{$r->id}}">
                                </label>
                            </td>
                            <td>
                                {{$r->competition->title}}
                            </td>
                            <td>
                                {{truncate($r->sql,25)}}
                            </td>
                            <td>
                                {{$r->value}}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
            <div class="modal-footer" id="modal-footerq">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close
                </button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" id="choose">Choose
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
            "dom": '<"top"flp>rt<"bottom"i><"clear">',
            "pagingType": "full_numbers",

            "columnDefs": [{
                "visible": false,
                "targets": groupColumn,
                // "targets": [1],
                // "searchable": false,
                // "orderable": false,
                // "visible": true
            }],
            // "scrollX": true
            "order": [
                [groupColumn, 'asc']
            ],
            // "displayLength": 25,
            "drawCallback": function (settings) {
                var api = this.api();
                var rows = api.rows({
                    page: 'current'
                }).nodes();
                var last = null;

                api.column(groupColumn, {
                    page: 'current'
                }).data().each(function (group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before(
                            '<tr class="group"><td colspan="6">' + group + '</td></tr>'
                        );

                        last = group;
                    }
                });
            }
        });

        // Order by the grouping
        $('#example tbody').on('click', 'tr.group', function () {
            var currentOrder = t.order()[0];
            if (currentOrder[0] === groupColumn && currentOrder[1] === 'asc') {
                t.order([groupColumn, 'desc']).draw();
            } else {
                t.order([groupColumn, 'asc']).draw();
            }
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

    function open_container() {
        var size = 'small',
            content = '',
            title = 'Ranking rules',
            footer = '';
        jQuery.noConflict();
        setModalBox(title, size);
        jQuery('#myModal').modal('show');

    }

    function setModalBox(title, content, footer, $size) {
        jQuery('#myModal').find('.modal-header h3').text(title);

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
                context: {
                    element: $(this)
                },
                data: {
                    _token: CSRF_TOKEN,
                    id: checkedIDs,
                    table: 'ranking_rules'
                },
                dataType: 'JSON',
                success: function (data) {
                    console.log(data);
                    if ('description' in data)
                        $('#description').val(data.description);
                    if ('name' in data)
                        $('#name').val(data.name);
                    if ('min' in data)
                        $('#min').val(data.min);
                    if ('max' in data)
                        $('#max').val(data.max);
                    if ('weight' in data)
                        $('#weight').val(data.weight);
                    if ('sql' in data)
                        $('#sql').val(data.sql);
                    if ('value' in data)
                        $('#value').val(data.value);
                },
                error: function (data) {
                    console.log(data);
                }
            });
        } else
            alert('Please Choose Ranking Rule!')
    });

</script>
@endsection
