<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>{{$person->last_name}}</title>
    <!-- <link rel="stylesheet" href="{{asset('css/app.css')}}"/> -->
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        h6 {
            opacity: 0.25;
        }

        .box-title {
            font-size: 24px;
        }

        .heading {
            color: #777;
        }

    </style>
</head>

<body class="container">
    <div class="card">
        <div class="card-header">Person details
        </div>

        <div>
            <div class="box-primary">
                <div class="box-header with-border">
                    <h4 class="box-title">{{$person->first_name}} {{$person->last_name}}</h4>
                </div>
                <hr>
                <div class="box-body col-md-12">
                    <div class="row">
                        <h4 class="heading">Biographical</h4>
                    </div>
                    <table>
                        <tr>
                            <td>
                                <strong>Birthdate:</strong>
                                <p>{{$person->birthdate}}</p>
                            </td>
                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            <td>
                                <strong>Birthplace:</strong>
                                <p>{{$person->birthplace}}</p>

                            </td>
                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            <td>
                                <strong>Sex:</strong>
                                <p>{{$person->sex}}</p>
                            </td>
                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            <td>
                                <strong>Nationality:</strong>
                                <p>{{$person->nationality}}</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Location:</strong>
                                <p>{{$person->state}}</p>
                            </td>
                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            <td>
                                <strong>Role:</strong>
                                <p>{{$person->type == 'contributor' ? 'Participant' : 'Support person'}}</p>
                            </td>
                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            <td>
                                <strong>Specializations:</strong>
                                @if(empty($person->specialization) || $person->specialization=='')
                                <p>None provided</p>
                                @else
                                <p>{{$person->specialization}}</p>
                                @endif
                            </td>
                        </tr>
                    </table>

                    @if(!empty($emails) && count($emails)>0)
                    <div class="row">
                        <h4 class="heading">Emails</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?php $step = 0; ?>
                            @foreach($emails as $email)
                            <?php $step++; ?>
                            <span><a href="mailto:{{$email->email}}">{{$email->email}}</a></span>
                            @if($step < count($emails)) , @endif @endforeach </div> </div> @else <div class="row">
                                <h6 class="heading">No emails provided</h6>
                        </div>
                        @endif

                        @if(!empty($addresses) && count($addresses)>0)
                        <div class="row">
                            <h4 class="heading">Addresses</h4>
                        </div>
                        @foreach($addresses as $address)
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Street:</strong>
                                <span>street</span>
                            </div>
                            <div class="col-md-6">
                                <strong>City:</strong>
                                <span>city</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Municipality/State:</strong>
                                <span>state</span>
                            </div>
                            <div class="col-md-6">
                                <strong>Country:</strong>
                                <span>country</span>
                            </div>
                        </div>
                        <hr>
                        @endforeach
                        @else
                        <div class="row">
                            <h6 class="heading">No addresses provided</h6>
                        </div>
                        @endif


                        @if(!empty($institutions) && count($institutions)>0)
                        <div class="row">
                            <h4 class="heading">Employment/Affiliations</h4>
                        </div>
                        @foreach($institutions as $institution)
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Institution/Company:</strong>
                                <span>{{$institution->institution_id > 0 ? $institutionslist[$institution->institution_id]->content : $institution->institution}}
                                </span>
                            </div>
                            <div class="col-md-4">
                                <strong>Title:</strong>
                                <span>{{$institution->title}}</span> (<span>{{$institution->type}}</span>)
                            </div>
                            <div class="col-md-2">
                                <strong>Start date:</strong><br />
                                <span>{{$institution->start}}</span>
                            </div>
                            <div class="col-md-2">
                                <strong>End date:</strong><br />
                                <span>{{$institution->end}}</span>
                            </div>
                        </div>
                        <hr>
                        @endforeach
                        @else
                        <div class="row">
                            <h6 class="heading">No employment/affiliation history provided</h6>
                        </div>
                        @endif


                        @if(!empty($degrees) && count($degrees)>0)
                        <div class="row">
                            <h4 class="heading">Education</h4>
                        </div>
                        @foreach($degrees as $degree)
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Institution:</strong>
                                <span>{{$degree->institution_id > 0 ? $institutionslist[$degree->institution_id]->content : $degree->institution}}</span>
                            </div>
                            <div class="col-md-4">
                                <strong>Degree:</strong>
                                <span>{{$degree->degree->text}}</span>
                            </div>
                            <div class="col-md-2">
                                <strong>Date:</strong>
                                <span>{{$degree->year}}</span>
                            </div>
                        </div>
                        <hr>
                        @endforeach
                        @else
                        <div class="row">
                            <h6 class="heading">No educational history provided</h6>
                        </div>
                        @endif


                        @if(!empty($honors) && count($honors)>0)
                        <div class="row">
                            <h4 class="heading">Honors</h4>
                        </div>
                        @foreach($honors as $honor)
                        <div class="row">
                            <div class="col-md-8">
                                <strong>Description:</strong>
                                <span>{{$honor->description}}</span>
                            </div>
                            <div class="col-md-4">
                                <strong>Date:</strong>
                                <span>{{$honor->year}}</span>
                            </div>
                        </div>
                        <hr>
                        @endforeach
                        @else
                        <div class="row">
                            <h6 class="heading">No honors provided</h6>
                        </div>
                        @endif


                        @if(!empty($books) && count($books)>0)
                        <div class="row">
                            <h4 class="heading">Books</h4>
                        </div>
                        @foreach($books as $book)
                        <div class="row">
                            <div class="col-md-12">
                                <strong>Title:</strong>
                                <span>{{$book->title}}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <strong>Publisher:</strong>
                                <span>{{$book->publisher}}</span>
                            </div>
                            <div class="col-md-4">
                                <strong>Year:</strong>
                                <span>{{$book->year}}</span>
                            </div>
                        </div>
                        <hr>
                        @endforeach
                        @else
                        <div class="row">
                            <h6 class="heading">No books provided</h6>
                        </div>
                        @endif


                        @if(!empty($meetings) && count($meetings)>0)
                        <div class="row">
                            <h4 class="heading">Meetings</h4>
                        </div>
                        @foreach($meetings as $meeting)
                        <div class="row">
                            <div class="col-md-12">
                                <strong>Description:</strong>
                                <span>{{$meeting->description}}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Year:</strong>
                                <span>{{$meeting->year}}</span>
                            </div>
                            <div class="col-md-4">
                                <strong>ANSEF supported:</strong>
                                <span>{{$meeting->ansef_supported == 1 ? 'Yes' : 'No'}}</span>
                            </div>
                            <div class="col-md-4">
                                <strong>Domestic:</strong>
                                <span>{{$meeting->domestic == 1 ? 'Yes' : 'No'}}</span>
                            </div>
                        </div>
                        <hr>
                        @endforeach
                        @else
                        <div class="row">
                            <h6 class="heading">No meeting attendance history provided</h6>
                        </div>
                        @endif


                        @if(!empty($publications) && count($publications)>0)
                        <div class="row">
                            <h4 class="heading">Publications</h4>
                        </div>
                        @foreach($publications as $publication)
                        <div class="row">
                            <div class="col-md-12">
                                <strong>Title:</strong>
                                <span>{{$publication->title}}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <strong>Year:</strong>
                                <span>{{$publication->year}}</span>
                            </div>
                            <div class="col-md-6">
                                <strong>Journal ref.:</strong>
                                <span>{{$publication->journal}}</span>
                            </div>
                            <div class="col-md-4">
                                <strong>ANSEF supported?:</strong>
                                <span>{{$publication->ansef_supported == 0 ? 'No' : 'Yes'}}</span>
                            </div>
                        </div>
                        @endforeach
                        @else
                        <div class="row">
                            <h6 class="heading">No publications provided</h6>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
</body>
