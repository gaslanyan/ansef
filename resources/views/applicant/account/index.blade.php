@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                <div class="card"  >
                    <div class="card-header">List of persons
                    <a href="{{action('Applicant\AccountController@create')}}"
                           class="display float-lg-right btn-primary px-2 myButton">Add A New Person</a></div>

                    <div class="card-body card_body" style="overflow:auto;">

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
                        @if(!empty($applicant_persons))
                        <table class="table table-responsive-md table-sm table-bordered display" id="example"
                               style="width:100%">
                            <thead>
                            <tr>
                                <th></th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Person's role</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($applicant_persons as $applicant_person)
                                <tr>
                                    <td></td>
                                    <td data-order="{{$applicant_person->first_name}}" data-search="{{$applicant_person->first_name}}"
                                        class="">
                                        {{$applicant_person->first_name}}
                                    </td>
                                    <td data-order="{{$applicant_person->last_name}}" data-search="{{$applicant_person->last_name}}"
                                        class="">
                                        {{$applicant_person->last_name}}
                                    </td>
                                    <td data-order="{{$applicant_person->type}}" data-search="{{$applicant_person->type}}" class="email_field">
                                        <?php {{echo($applicant_person->type == 'support' ? 'Support person' : 'Project participant');}}?>
                                        </input>
                                    </td>
                                    <td>
                                        <input type="hidden" class="id" value="{{$applicant_person->id}}">

                                        <a href="{{action('Applicant\PersonController@show', $applicant_person->id)}}" title="View">
                                            <span class="fas fa-eye myButton">View</spani>
                                        </a>

                                        <a href="{{action('Applicant\PersonController@edit', $applicant_person->id)}}" title="Full Edit">
                                            <span class="fa fa-edit myButton">Edit</span>
                                        </a>

                                        @if($applicant_person->assigned)
                                        @else
                                        <a href="{{action('Applicant\PersonController@destroy', $applicant_person->id)}}"
                                           onclick="return confirm('Are you sure you want to delete the person?')" >
                                            <span class="fa fa-trash myButton">Delete</span></a>
                                        @endif
                                        <br/>
                                        <a href="{{action('Applicant\AddressController@create', $applicant_person->id)}}" title="Show Addresses" class="add_address">
                                            <?php if (empty(getAddressesByPersonID($applicant_person->id))) {
                                                echo " <span class='fas fa-address-card myButton' style='color:#dd4b39 !important;'>Addresses</span>";
                                            } else {
                                                echo " <span class='fas fa-address-card myButton'>Addresses</span>";
                                            } ?></a>

                                        <a href="{{action('Applicant\EmailController@create', $applicant_person->id)}}" title="Show Emails" class="add_email">
                                            <?php if (empty(getEmailByPersonID($applicant_person->id))) {
                                                echo " <span class='fa fa-envelope-open myButton' style='color:#dd4b39 !important;'>Emails</span>";
                                            } else {
                                                echo " <span class='fa fa-envelope-open myButton'>Emails</span>";
                                            } ?>
                                        </a>
                                        <a href="{{action('Applicant\PhoneController@create', $applicant_person->id)}}" title="Show Phones" class="add_phone"><span class="fa fa-phone myButton">Phone numbers</span>
                                        </a>
                                        <br />
                                        <a href="{{action('Applicant\InstitutionController@create', $applicant_person->id)}}" title="Show Institutions" class="add_institutions"><span class="fa fa-university myButton">Employment</span>
                                        </a>
                                        @if($applicant_person->type =='participant')
                                        <a href="{{action('Applicant\DegreePersonController@create', $applicant_person->id)}}" title="Show Degrees" class="add_degrees"><span class="fa fa-graduation-cap myButton">Education</span>
                                        </a><br/>
                                        <a href="{{action('Applicant\BookController@create', $applicant_person->id)}}" title="Show Books" class="add_institutions"><span class="fa fa-book myButton">Books</span>
                                        </a>
                                        <a href="{{action('Applicant\MeetingController@create', $applicant_person->id)}}" title="Show Meetings" class="add_meetings"><span class="fa fa-user-friends myButton">Meetings</span>

                                        </a>
                                        <a href="{{action('Applicant\PublicationsController@create', $applicant_person->id)}}" title="Show Publications" class="add_publications"><span class="fas fa-sticky-note myButton">Publications</span>
                                        </a>
                                        <a href="{{action('Applicant\HonorsController@create', $applicant_person->id)}}" title="Show Honors&Grants" class="add_honors"><span class="fa fa-trophy myButton">Honors</span>
                                        </a>
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                            @else
                            <p><i class="fas fa-question-circle text-blue"></i></i><span style="color:#777;margin-left:10px;">No persons have been
                            added to your account.
                            Before you add any proposal, you need to add at least one person in the role of a project participant.</p>
                            <p style="color:#777;">
                            In this ANSEF portal account, you can maintain a list of persons in one of two roles:<br/>
                            <li style="color:#777;">
                                a <b>participant</b> who can be either a principal investigator that heads a project,
                                or a collaborator/consultant who can participate in the project in a secondary role;
                            </li>
                            <li style="color:#777;">
                                a <b>support person</b> who will not partipate in any project but provide indirect support as a director of an
                                institute or by writing a letter of recommendation in support of a project.
                            </li>
                             </p>
                            <p style="color:#777;">
                            When you create a project proposal, you will be able to choose from this list of persons.
                            Note that a support person cannot be added to any proposal as a participant;
                            and a participant cannot be added to any proposal as a support person. So, make sure you choose the role for each person
                            here accordingly. You can add as many persons to your account as needed. A person can be attached to several proposals; and a proposal can have some or all the persons listed here.
                            The list of persons in your account will be stored on the ANSEF portal for use again for future proposals you might submit.
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
                    "columnDefs": [
                        {
                            "targets": [4],
                            "searchable": false
                        }, {
                            "targets": [4],
                            "searchable": false
                        }
                    ]
                });
                t.on('order.dt search.dt', function () {
                    t.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }).draw();
            });

        </script>
@endsection
