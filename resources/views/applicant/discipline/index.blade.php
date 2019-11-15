@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 offset-md-2">
                 <div class="card" style="margin-top:20px;">
                    <div class="card-header">Լist of Discipline</div>
                    <div class="card-body card_body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div><br/>
                        @endif
                        @if (\Session::has('success'))
                            <div class="alert alert-success">
                                <p>@php echo html_entity_decode(\Session::get('success'), ENT_HTML5) @endphp</p>
                            </div><br/>
                        @endif
                        <table class="table table-responsive-md table-sm table-bordered display" id="example">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Title</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($disciplines as $discipline)
                                <tr>
                                    <td></td>
                                    <td>{{$discipline['title']}}</td>
                                    <td>
                                        <a href="{{action('Base\DisciplineController@edit', $discipline['id'])}}"
                                           class="">
                                            <i class="fa fa-pencil-alt"></i>
                                        </a>
                                        <form action="{{action('Base\DisciplineController@destroy', $discipline['id'])}}"
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
                "paging": "full_numbers"
            });
            t.on('order.dt search.dt', function () {
                t.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        });
    </script>
@endsection
