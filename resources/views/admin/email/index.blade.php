@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-12">
                 <div class="card" >
                    <div class="card-header">List of emails
                        <a href="{{action('Admin\EmailController@create')}}"
                           class="display float-lg-right btn-primary px-2">Add an email</a>
                    </div>
                        @include('partials.status_bar')



                    <div class="card-body card_body" style="overflow:auto;">
                        @if(!empty($emails))
                            <table class="table table-responsive-md table-sm table-bordered display" id="example"
                                   style="width:100%">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($emails as $email)
                                    <tr>
                                        <td></td>
                                        <td data-order="{{$email['email']}}" data-search="{{$email['email']}}"
                                            class="email_field">
                                            <input type="text" class="form-control" name="email"
                                                   value="{{$email['email']}}" disabled>
                                        </td>
                                        <td>
                                            <input type="hidden" class="id" value="{{$email['id']}}">
                                            {{--<button title="Edit"--}}
                                            {{--class="edit btn-link"><i class="fa fa-pencil-alt"></i>--}}
                                            {{--</button>--}}
                                            {{--<button title="Cancel"--}}
                                            {{--class="cancel editable btn-link"><i class="fa fa-ban"></i>--}}
                                            {{--</button>--}}
                                            {{--<a href="{{action('Admin\PersonController@show', $email['id'])}}"--}}
                                            {{--class="view" title="View"><i class="fa fa-eye"></i>--}}
                                            {{--</a>--}}

                                            <a href="{{action('Admin\EmailController@edit', $email['id'])}}"
                                               title="full_edit"
                                               class="full_edit"><i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{action('Admin\EmailController@destroy', $email['id'])}}"
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
