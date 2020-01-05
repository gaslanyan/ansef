@extends('layouts.master')

@section('content')
<div class="container">

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">List of Persons
                    {{--<a href="{{action('Applicant\PersonController@create')}}"--}}
                    {{--class="display float-lg-right btn-primary px-2">Create Person</a>--}}
                </div>
                @if (\Session::has('success'))
                <div class="alert alert-success">
                    <p>@php echo html_entity_decode(\Session::get('success'), ENT_HTML5) @endphp</p>
                </div><br />
                @elseif (\Session::has('wrong'))
                <div class="alert alert-success">
                    <p>@php echo html_entity_decode( \Session::get('wrong'), ENT_HTML5) @endphp</p>
                </div><br />
                @endif


                <div class="card-body" style="overflow:auto;">
                    @if(!empty($persons))
                    <table class="table table-responsive-md table-sm table-bordered display compact" id="example">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Birth Date</th>
                                <th>Birth Place</th>
                                <th>Sex</th>
                                <th>State</th>
                                <th>Nationality</th>
                                <th>Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($persons as $person)
                            <tr>

                                <td data-order="{{$person['id']}}" data-search="{{$person['id']}}">{{$person['id']}}
                                </td>
                                <td data-order="{{$person['first_name']}}" data-search="{{$person['first_name']}}">
                                    {{$person['first_name']}}</td>
                                <td data-order="{{$person['last_name']}}" data-search="{{$person['last_name']}}">
                                    {{$person['last_name']}}</td>
                                <td data-order="{{$person['birthdate']}}" data-search="{{$person['birthdate']}}">
                                    {{$person['birthdate']}}</td>
                                <td data-order="{{$person['birthplace']}}" data-search="{{$person['birthplace']}}">
                                    {{$person['birthplace']}}</td>
                                <td data-order="{{$person['sex']}}" data-search="{{$person['sex']}}">{{$person['sex']}}
                                </td>
                                <td data-order="{{$person['state']}}" data-search="{{$person['state']}}">
                                    {{$person['state']}}</td>
                                <td data-order="{{$person['nationality']}}" data-search="{{$person['nationality']}}">
                                    {{$person['nationality']}}</td>
                                <td data-order="{{$person['type']}}" data-search="{{$person['type']}}">
                                    {{$person['type']}}</td>
                                <td> <a href="{{action('Applicant\PersonController@show', $person['id'])}}" class="view"
                                        title="View"><i class="fa fa-eye"></i>
                                    </a>

                                    <a href="{{action('Applicant\PersonController@edit', $person['id'])}}"
                                        title="full_edit" class="full_edit"><i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{action('Applicant\PersonController@destroy', $person['id'])}}"
                                        method="post">
                                        @csrf
                                        <input name="_method" type="hidden" value="DELETE">
                                        <button title="Delete" class="editable btn-link"><i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p><i class="fas fa-question-circle text-blue"></i></i><span style="color:#777;margin-left:10px;">No
                            persons have been
                            added to your account.
                            Before you add any proposal, you need to add at least one person in the role of a project
                            participant.</p>
                    <p style="color:#777;">
                        In this ANSEF portal account, you can maintain a list of persons in one of two roles:<br />
                        <li style="color:#777;">
                            a <b>participant</b> who can be either a principal investigator that heads a project,
                            or a collaborator/consultant who can participate in the project in a secondary role;
                        </li>
                        <li style="color:#777;">
                            a <b>support person</b> who will not partipate in any project but provide indirect support
                            as a director of an
                            institute or by writing a letter of recommendation in support of a project.
                        </li>
                    </p>
                    <p style="color:#777;">
                        When you create a project proposal, you will be able to choose from this list of persons.
                        Note that a support person cannot be added to any proposal as a participant;
                        and a participant cannot be added to any proposal as a support person. So, make sure you choose
                        the role for each person
                        here accordingly. You can add as many persons to your account as needed. A person can be
                        attached to several proposals; and a proposal can have some or all the persons listed here.
                        The list of persons in your account will be stored on the ANSEF portal for use again for future
                        proposals you might submit.
                        </span>
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        var t = $('#example').DataTable({
            "pagingType": "full_numbers",
            "dom": '<"top"flp>rt<"bottom"i><"clear">',
            columnDefs: [{
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
