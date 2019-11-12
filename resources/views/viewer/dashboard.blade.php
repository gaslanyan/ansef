@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body card_body">
                        @if(!empty($competitonlist))
                            <table class="table table-responsive-md table-sm table-bordered display" id="example"
                                   style="width:100%">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Deadline</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($competitonlist as $comp)
                                    <tr>
                                        <td></td>
                                        <td data-order="{{$comp['title']}}" data-search="{{$comp['title']}}"
                                            class="email_field">
                                            <input type="text" class="form-control" name="email"
                                                   value="{{$comp['title']}}" disabled>
                                        </td>
                                        <td data-order="{{$comp['description']}}" data-search="{{$comp['description']}}"
                                            class="email_field">
                                            <input type="text" class="form-control" name="email"
                                                   value="{{$comp['description']}}" disabled>
                                        </td>
                                        <td data-order="{{$comp['submission_end_date']}}" data-search="{{$comp['submission_end_date']}}"
                                            class="email_field">
                                            <input type="text" class="form-control" name="email"
                                                   value="{{$comp['submission_end_date']}}" disabled>
                                        </td>

                                    </tr>
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
@endsection