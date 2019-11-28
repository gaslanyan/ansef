   <div class="card-body card_body">

                        <div class="box-primary">
                            <div class="box-header with-border">
                                <h4 class="box-title">{{$person->first_name}} {{$person->last_name}}</h4>
                            </div>

                            <div class="box-body col-md-12">
                                <div class="row" >
                                    <h4 style="color:#777;">Biographical</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Birthdate:</strong>
                                            <p>{{$person->birthdate}}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Birthplace:</strong>
                                            <p>{{$person->birthplace}}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Sex:</strong>
                                            <p>{{$person->sex}}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Nationality:</strong>
                                            <p>{{$person->nationality}}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Location:</strong>
                                            <p>{{$person->state}}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Role:</strong>
                                            <p>{{$person->type == 'contributor' ? 'Participant' : 'Support person'}}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <strong>Specializations:</strong>
                                            @if(empty($person->specialization) || $person->specialization=='')
                                            <span>None provided</span>
                                            @else
                                            <span>{{$person->specialization}}</span>
                                            @endif
                                    </div>
                                </div>
                                <br/>
                                @if(!empty($emails) && count($emails)>0)
                                <div class="row" >
                                    <h4 style="color:#777;">Emails</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                            <?php $step = 0; ?>
                                            @foreach($emails as $email)
                                            <?php $step++; ?>
                                            <span><a href="mailto:{{$email->email}}">{{$email->email}}</a></span>
                                            @if($step < count($emails))
                                                ,
                                            @endif
                                            @endforeach
                                    </div>
                                </div>
                                @else
                                <div class="row" >
                                    <h6 style="color:#777;">No emails provided</h6>
                                </div>
                                @endif
                                <br/>
                                @if(!empty($addresses) && count($addresses)>0)
                                <div class="row" >
                                    <h4 style="color:#777;">Addresses</h4>
                                </div>
                                @foreach($addresses as $address)
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Street:</strong>
                                            <span>{{$address->street}}</span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>City:</strong>
                                            <span>{{$address->city}}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Municipality/State:</strong>
                                            <span>{{$address->province}}</span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Country:</strong>
                                            <span>{{$address->country->country_name}}</span>
                                    </div>
                                </div>
                                <hr>
                                @endforeach
                                @else
                                <div class="row" >
                                    <h6 style="color:#777;">No addresses provided</h6>
                                </div>
                                @endif

                                <br/>

                                @if(!empty($institutions) && count($institutions)>0)
                                <div class="row" >
                                    <h4 style="color:#777;">Employment/Affiliations</h4>
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
                                        <strong>Start date:</strong><br/>
                                            <span>{{$institution->start}}</span>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>End date:</strong><br/>
                                            <span>{{$institution->end}}</span>
                                    </div>
                                </div>
                                <hr>
                                @endforeach
                                @else
                                <div class="row" >
                                    <h6 style="color:#777;">No employment/affiliation history provided</h6>
                                </div>
                                @endif

                                <br/>

                                @if(!empty($degrees) && count($degrees)>0)
                                <div class="row" >
                                    <h4 style="color:#777;">Education</h4>
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
                                <div class="row" >
                                    <h6 style="color:#777;">No educational history provided</h6>
                                </div>
                                @endif

                                <br/>

                                @if(!empty($honors) && count($honors)>0)
                                <div class="row" >
                                    <h4 style="color:#777;">Honors</h4>
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
                                <div class="row" >
                                    <h6 style="color:#777;">No honors provided</h6>
                                </div>
                                @endif

                                <br/>

                                @if(!empty($books) && count($books)>0)
                                <div class="row" >
                                    <h4 style="color:#777;">Books</h4>
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
                                <div class="row" >
                                    <h6 style="color:#777;">No books provided</h6>
                                </div>
                                @endif

                                <br/>

                                @if(!empty($meetings) && count($meetings)>0)
                                <div class="row" >
                                    <h4 style="color:#777;">Meetings</h4>
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
                                <div class="row" >
                                    <h6 style="color:#777;">No meeting attendance history provided</h6>
                                </div>
                                @endif

                                <br/>

                                @if(!empty($publications) && count($publications)>0)
                                <div class="row" >
                                    <h4 style="color:#777;">Publications</h4>
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
                                <div class="row" >
                                    <h6 style="color:#777;">No publications provided</h6>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-12" style="margin-top:30px;">
                            <a href="{{action('Applicant\PersonController@download', $person->id)}}" class="btn btn-primary">Download</a>
                            <a href="{{action('Applicant\PersonController@index') }}" class="btn btn-secondary">Go Back</a>
                        </div>

                    </div>
                </div>
