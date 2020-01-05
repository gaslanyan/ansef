@extends('layouts.master')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">List of auto-generated messages
                    <a href="{{action('Admin\TemplateController@create')}}"
                        class="display float-lg-right btn-primary px-2 myButton"><i class="fas fa-plus"></i>&nbsp;Add a
                        message</a>


                </div>

                <div class="card-body" style="overflow:auto;">
                    @include('partials.status_bar')

                    <table class="table table-responsive-md table-sm table-bordered display" id="example"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th width="200">Text</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($templates as $template)
                            <tr>
                                <td></td>

                                <td>{{$template['name']}}</td>
                                <td width="200">{{$template['text']}}</td>
                                <td><a href="{{action('Admin\TemplateController@edit', $template['id'])}}" class=""><i
                                            class="fa fa-pencil-alt"></i></a>
                                    <form action="{{action('Admin\TemplateController@destroy', $template['id'])}}"
                                        method="post">
                                        @csrf
                                        <input name="_method" type="hidden" value="DELETE">
                                        <button class="btn-link" type="submit"><i class="fa fa-trash"></i></button>
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
            "dom": '<"top"flp>rt<"bottom"i><"clear">',
            "pagingType": "full_numbers",
            "scrollX": true,
            "columnDefs": [{
                targets: [0],
                orderData: [0, 1]
            }, {
                targets: [1],
                orderData: [1, 0]
            }]
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
