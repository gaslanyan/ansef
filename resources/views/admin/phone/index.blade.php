@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">List of phone numbers
                    <a href="{{action('Admin\PhoneController@create')}}"
                        class="display float-lg-right btn-primary px-2">Add a phone number</a>
                </div>
                @include('partials.status_bar')


                <div class="card-body" style="overflow:auto;">
                    @if(!empty($phones))
                    <table class="table table-responsive-md table-sm table-bordered display" id="example"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Country Code</th>
                                <th>Phone Number</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($phones as $phone)
                            <tr>
                                <td></td>
                                <td data-order="{{$phone['country_code']}}" data-search="{{$phone['country_code']}}">
                                    {{$phone['country_code']}}</td>
                                <td data-order="{{$phone['number']}}" data-search="{{$phone['number']}}">
                                    {{$phone['number']}}</td>
                                <td>
                                    <a href="{{action('Admin\PhoneController@edit', $phone['id'])}}"
                                        title="full_edit"><i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{action('Admin\PhoneController@destroy', $phone['id'])}}"
                                        method="post">
                                        @csrf
                                        <input name="_method" type="hidden" value="DELETE">
                                        <button type="submit" class=" btn-link"><i class="fa fa-trash"></i>
                                        </button>
                                    </form>
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
<script>
    $(document).ready(function () {
        var t = $('#example').DataTable({
            "dom": '<"top"flp>rt<"bottom"i><"clear">',
            "paging": false

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
