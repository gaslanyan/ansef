@extends('layouts.master')

@section('content')
    <div class="container">
        @if (\Session::has('success'))
            <div class="alert alert-success">
                <p>@php echo html_entity_decode(\Session::get('success'), ENT_HTML5) @endphp</p>
            </div><br/>
        @endif
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-12">
                <div class="card">
                    <div class="card-header">Proposal
                        
                    </div>

                    <div class="card-body card_body">

                        <div class="box-body col-md-12">
                            <div class="row">
                                @if(!empty($competitions_lists))
                                    <div class="form-group col-lg-12 align-items-center">
                                        <select class="form-control -align-center comp_list" name="comp_prop" id="comp_list">
                                            <option value="choosecompetition">Choose the competition</option>
                                            @foreach($competitions_lists as $competition)
                                                <option value="{{$competition['id']}}">{{$competition['title']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                            </div>
                            <hr>
                        </div>

                        <div class="prop">

                        </div>
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
