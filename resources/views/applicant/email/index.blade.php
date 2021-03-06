@extends('layouts.master')

@section('content')
<div class="container">

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                @include('partials.status_bar')

                <div class="card-header">List of Persons
                    <a href="{{action('Applicant\AccountController@create')}}"
                        class="display float-lg-right btn-primary px-2 myButton"><i class="fas fa-plus"></i>&nbsp;Add A
                        New Person</a></div>

                <div class="card-body" style="overflow:auto;">
                    @if(!empty($persons))
                    <i class="fas fa-question-circle all" style="color:#dd4b39;"></i> <span style="color:#dd4b39;">
                        Items in red are required and missing</span><br />
                    <i class="fas fa-question-circle all" style="color:#777;"></i> <span style="color:#777;"> Items in
                        gray are optional</span>
                    <table class="table table-responsive-md table-sm table-bordered display" id="example"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Full Name</th>
                                <th>Role</th>
                                <th>Update</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($persons as $person)
                            <tr>
                                <td></td>
                                <td data-order="{{$person['first_name']}}" data-search="{{$person['first_name']}}"
                                    class="email_field">
                                    <input type="text" class="form-control" name="email"
                                        value="{{$person['first_name']." ".$person['last_name']}}" disabled>
                                </td>
                                <td data-order="{{$person['type']}}" data-search="{{$person['type']}}"
                                    class="email_field">
                                    <input type="text" class="form-control" name="email"
                                        value="{{ucfirst($person['type'])}}" disabled>
                                </td>
                                <td style="color:#777;">
                                    <input type="hidden" class="id" value="{{$person['id']}}">
                                    <a href="{{action('Applicant\AddressController@create', $person['id'])}}"
                                        title="Show Addresses" class="add_address">
                                        <?php if (empty(getAddressesByPersonID($person['id']))) {
                                            echo " <span class='fas fa-address-card myButton' style='color:#dd4b39 !important;'>Addresses</span>";
                                        } else {
                                            echo " <span class='fas fa-address-card myButton'>Addresses</span>";
                                        } ?></a>

                                    <a href="{{action('Applicant\EmailController@create', $person['id'])}}"
                                        title="Show Emails" class="add_email">
                                        <?php if (empty(getEmailByPersonID($person['id']))) {
                                            echo " <span class='fa fa-envelope-open myButton' style='color:#dd4b39 !important;'>Emails</span>";
                                        } else {
                                            echo " <span class='fa fa-envelope-open myButton'>Emails</span>";
                                        } ?>
                                    </a>
                                    <a href="{{action('Applicant\PhoneController@create', $person['id'])}}"
                                        title="Show Phones" class="add_phone"><span class="fa fa-phone myButton">Phone
                                            numbers</span>
                                    </a>
                                    <a href="{{action('Applicant\InstitutionController@create', $person['id'])}}"
                                        title="Show Institutions" class="add_institutions"><span
                                            class="fa fa-university myButton">Employment</span>
                                    </a><br />

                                    @if($person['type']=='participant')
                                    <a href="{{action('Applicant\DegreePersonController@create', $person['id'])}}"
                                        title="Show Degrees" class="add_degrees"><span
                                            class="fa fa-graduation-cap myButton">Education</span>
                                    </a>
                                    <a href="{{action('Applicant\BookController@create', $person['id'])}}"
                                        title="Show Books" class="add_institutions"><span
                                            class="fa fa-book myButton">Books</span>
                                    </a>
                                    <a href="{{action('Applicant\MeetingController@create', $person['id'])}}"
                                        title="Show Meetings" class="add_meetings"><span
                                            class="fa fa-user-friends myButton">Meetings</span>

                                    </a>
                                    <a href="{{action('Applicant\PublicationsController@create', $person['id'])}}"
                                        title="Show Publications" class="add_publications"><span
                                            class="fas fa-sticky-note myButton">Publications</span>
                                    </a>
                                    <a href="{{action('Applicant\HonorsController@create', $person['id'])}}"
                                        title="Show Honors&Grants" class="add_honors"><span
                                            class="fa fa-trophy myButton">Honors</span>
                                    </a>
                                    @endif
                                    {{--<form action="{{action('Applicant\EmailController@destroy', $person['id'])}}"
                                    method="post">--}}
                                    {{--@csrf--}}
                                    {{--<input name="_method" type="hidden" value="DELETE">--}}
                                    {{--<button type="submit" class=" btn-link"><i class="fa fa-trash"></i></button>--}}
                                    {{--</form>--}}


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
                        Once you add a person in a participant role, you can come to this section to add additional CV
                        data about
                        the person, such as publications, educational background, etc...
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
            //"pagingType": "full_numbers",
            "paging": false,
            "columnDefs": [{
                "targets": [2],
                "searchable": false
            }, {
                "targets": [2],
                "searchable": false
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
