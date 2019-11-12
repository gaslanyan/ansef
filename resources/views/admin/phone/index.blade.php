@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 offset-md-2">
                <div class="card">
                    <div class="card-header">List of phone numbers
                        <a href="{{action('Admin\PhoneController@create')}}"
                           class="display float-lg-right btn-primary px-2">Add a phone number</a>
                    </div>
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

                    <div class="card-body card_body">
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
                                        <td data-order="{{$phone['country_code']}}"
                                            data-search="{{$phone['country_code']}}">{{$phone['country_code']}}</td>
                                        <td data-order="{{$phone['number']}}"
                                            data-search="{{$phone['number']}}">{{$phone['number']}}</td>
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
                //"pagingType": "full_numbers",
                "paging": false

            });
            t.on('order.dt search.dt', function () {
                t.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        });

    </script>
@endsection
