@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                <div class="card" style="margin-top:20px;">
                    @if (\Session::has('success'))
                        <div class="alert alert-success">
                            <p>@php echo html_entity_decode(\Session::get('success'), ENT_HTML5) @endphp</p>
                        </div><br/>
                    @elseif (\Session::has('wrong'))
                        <div class="alert alert-success">
                            <p>{{ \Session::get('wrong') }}</p>
                        </div><br/>
                    @endif
                    <div class="card-header">List of Persons
                        <a href="{{action('Applicant\AccountController@create')}}"
                           class="display float-lg-right btn-primary px-2">Add A New Person</a></div>

                    <div class="card-body card_body">
                        @if(!empty($persons))
                            <i class="fas fa-question-circle all" style="color:#dd4b39;"></i> <span style="color:#dd4b39;"> Items in red are required and missing</span><br/>
                            <i class="fas fa-question-circle all" style="color:#777;"></i> <span style="color:#777;"> Items in gray are optional</span>
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
                                        <td data-order="{{$person['first_name']}}"
                                            data-search="{{$person['first_name']}}"
                                            class="email_field">
                                            <input type="text" class="form-control" name="email"
                                                   value="{{$person['first_name']." ".$person['last_name']}}" disabled>
                                        </td>
                                        <td data-order="{{$person['type']}}" data-search="{{$person['type']}}"
                                            class="email_field">
                                            <input type="text" class="form-control" name="email"
                                                   value="{{$person['type'] == 'contributor' ? 'Participant' : 'Support'}}" disabled>
                                        </td>
                                        <td style="color:#777;">
                                            <input type="hidden" class="id" value="{{$person['id']}}">
                                            {{--<button title="Edit"--}}
                                            {{--class="edit btn-link"><i class="fa fa-pencil-alt"></i>--}}
                                            {{--</button>--}}
                                            {{--<button title="Cancel"--}}
                                            {{--class="cancel editable btn-link"><i class="fa fa-ban"></i>--}}
                                            {{--</button>--}}
                                            {{--<a href="{{action('Admin\PersonController@show', $email['id'])}}"--}}
                                            {{--class="view" title="View"><i class="fa fa-eye"></i>--}}
                                            {{--</a>--}}

                                            {{--<a href="{{action('Applicant\EmailController@edit', $email->id)}}"--}}
                                            {{--title="full_edit"--}}
                                            {{--class="full_edit"><i class="fa fa-edit"></i>--}}
                                            {{--</a>--}}
                                            
                                            <!-- VVS -->
                                            {{-- <a href="{{action('Applicant\PersonController@addresses', $person['id'])}}" title="Show Addresses" class="add_address">  --}}
                                                <a><?php if(empty(getAddressesByPersonID($person['id']))){
                                                    echo " <i class='fas fa-address-card' style='color:#dd4b39 !important;'></i>&nbsp;<span style='color:#dd4b39 !important;'>Addresses</span>";
                                                }
                                                else{
                                                    echo " <i class='fas fa-address-card'></i>&nbsp;Addresses";
                                                }?></a>&nbsp;|
                                            <!--</a><br>-->
                                            
                                            <a href="{{action('Applicant\EmailController@create', $person['id'])}}" title="Show Emails" class="add_email">
                                                <?php if(empty(getEmailByPersonID($person['id']))){
                                                    echo " <i class='fa fa-envelope-open' style='color:#dd4b39 !important;'></i>&nbsp;<span style='color:#dd4b39 !important;'>Emails</span>";
                                                }
                                                else{
                                                    echo " <i class='fa fa-envelope-open'></i>&nbsp;Emails";
                                                }?>
                                            </a>&nbsp;|
                                            <a href="{{action('Applicant\PhoneController@create', $person['id'])}}" title="Show Phones" class="add_phone"><i class="fa fa-phone"></i>
                                            Phone numbers</a>&nbsp;|
                                            <a href="{{action('Base\InstitutionController@create', $person['id'])}}" title="Show Institutions"
                                               class="add_institutions"><i class="fa fa-university"></i>
                                            Employment</a><br/>
                                            <a href="{{action('Applicant\DegreePersonController@create', $person['id'])}}" title="Show Degrees"
                                               class="add_degrees"><i class="fa fa-graduation-cap"></i>
                                            Education</a>&nbsp;|
                                            <a href="{{action('Applicant\DisciplineController@create', $person['id'])}}" title="Show Disciplines"
                                               class="add_disciplines"><i class="fas fa-wrench"></i>
                                            Specialties</a><br/>

                                            @if($person['type']=='contributor')
                                                <a href="{{action('Base\BookController@create', $person['id'])}}" title="Show Books"
                                                   class="add_institutions"><i class="fa fa-book"></i>
                                                Books</a>&nbsp;|
                                                <a href="{{action('Base\MeetingController@create', $person['id'])}}" title="Show Meetings"
                                                   class="add_meetings"><i class="fa fa-user-friends"></i>
                                                Meetings
                                                </a>&nbsp;|
                                                <a href="{{action('Base\PublicationsController@create', $person['id'])}}" title="Show Publications"
                                                   class="add_publications"><i class="fas fa-sticky-note"></i>
                                                Publications</a>&nbsp;|
                                                <a href="{{action('Base\HonorsController@create', $person['id'])}}" title="Show Honors&Grants"
                                                   class="add_honors"><i class="fa fa-trophy"></i>
                                                Honors</a>
                                            @endif
                                            {{--<form action="{{action('Applicant\EmailController@destroy', $person['id'])}}" method="post">--}}
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
                            <p><i class="fas fa-question-circle text-blue"></i></i><span style="color:#777;margin-left:10px;">No persons have been
                            added to your account. 
                            Before you add any proposal, you need to add at least one person in the role of a project participant.</p>
                            <p style="color:#777;">
                            Once you add a person in a participant role, you can come to this section to add additional CV data about 
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
                "columnDefs": [
                    {
                        "targets": [2],
                        "searchable": false
                    }, {
                        "targets": [2],
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
