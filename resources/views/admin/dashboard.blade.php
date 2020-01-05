@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body" style="overflow:auto;">
                    @if(!empty($proposals))

                    <table class="table table-responsive-md table-sm table-bordered display" id="example">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Competition Title</th>
                                <th>Proposals Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($proposals as $p)
                            <tr>
                                <td></td>
                                <td data-order="{{$p->title}}" data-search="{{$p->title}}" class="title">
                                    {{$p->title}}
                                </td>
                                @php
                                $count_f =$p->proposalsCount->first();
                                if(!empty($count_f))
                                $count = $count_f->p_count;
                                else
                                $count = 0;
                                @endphp
                                <td data-order="{{$count}}" data-search="{{$count}}" class="count">
                                    {{$count}}
                                </td>
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
            "dom": '<"top"flp>rt<"bottom"i><"clear">',
            "pagingType": "full_numbers"
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
