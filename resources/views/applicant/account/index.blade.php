@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                <div class="card"  style="margin-top:20px;">
                    <div class="card-header">List of persons
                    <a href="{{action('Applicant\AccountController@create')}}"
                           class="display float-lg-right btn-primary px-2">Add A New Person</a></div>

                    <div class="card-body card_body">

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
                                        class="email_field">
                                        <input type="text" class="form-control" name="firstname"
                                               value="{{$applicant_person->first_name}}" disabled>
                                    </td>
                                    <td data-order="{{$applicant_person->last_name}}" data-search="{{$applicant_person->last_name}}"
                                        class="email_field">
                                        <input type="text" class="form-control" name="lastname"
                                               value="{{$applicant_person->last_name}}" disabled>
                                    </td>
                                    <!--td data-order="{{$applicant_person->state}}" data-search="{{$applicant_person->state}}"
                                        class="email_field">
                                        <select id="type" class="form-control" name="type" disabled>
                                            {{--<?php $enum = getEnumValues('persons', 'state');?>--}}
                                            {{--@if(!empty($enum))--}}
                                                {{--@foreach($enum as $item)--}}
                                                        {{--@if($applicant_person->state == $item)--}}
                                                            {{--<option class="text-capitalize" value="{{$item}}" selected>{{$item}}</option>--}}
                                                        {{--@else--}}
                                                            {{--<option class="text-capitalize" value="{{$item}}">{{$item}}</option>--}}
                                                        {{--@endif--}}
                                                {{--@endforeach--}}
                                            {{--@endif--}}
                                        </select>
                                    </td-->
                                    <td data-order="{{$applicant_person->type}}" data-search="{{$applicant_person->type}}" class="email_field">
                                        <?php {{echo($applicant_person->type == 'external_support' ? 'Support person' : 'Project participant');}}?>
                                        </input>
                                    </td>
                                    <td>
                                        <input type="hidden" class="id" value="{{$applicant_person->id}}">
                                        <!--<button title="Edit"-->
                                        <!--        class="edit btn-link"><i class="fa fa-pencil-alt"></i>-->
                                        <!--</button>-->
                                        <!--<button title="Save"-->
                                        <!--        class="save_app editable btn-link"><i class="fa fa-save"></i>-->
                                        <!--</button>-->
                                        <!--<button title="Cancel"-->
                                        <!--        class="cancel editable btn-link"><i class="fa fa-ban"></i>-->
                                        <!--</button>-->

                                        <!-- VVS -->
                                        <!-- We need a show page for a person. Currently it doesn't show. I forgot to mention it in 0.4 -->
                                        <a href="{{action('Applicant\PersonController@show', $applicant_person->id)}}"
                                           class="view" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <a href="{{action('Applicant\PersonController@edit', $applicant_person->id)}}"
                                           class="full-edit" title="Full Edit"><i class="fa fa-pencil-alt"></i>
                                        </a>

                                        <!--<a href="{{action('Applicant\AccountController@show', $applicant_person->id)}}"-->
                                        <!--   class="view" title="View"><i class="fa fa-eye"></i>-->
                                        <!--</a>-->
                                        <a href="{{action('Applicant\PersonController@destroy', $applicant_person->id)}}"
                                           class="btn-link col-lg-2 delete">
                                            <i class="fa fa-trash"></i></a>
                                        {{--<a href="{{action('Admin\PersonController@edit', $user->group_person_id)}}"--}}
                                           {{--title="full_edit"--}}
                                           {{--class="full_edit"><i class="fa fa-edit"></i>--}}
                                        {{--</a>--}}
                                        {{--<form action="{{action('Applicant\PersonController@destroy', $applicant_person->id)}}" method="post">--}}
                                            {{--@csrf--}}
                                            {{--<input name="_method" type="hidden" value="DELETE">--}}
                                        {{--<button title="Delete"--}}
                                                {{--class="editable btn-link"><i class="fa fa-trash"></i>--}}
                                        {{--</button>--}}
                                        </form>
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