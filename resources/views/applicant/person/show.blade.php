@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                <div class="card" style="margin-top:20px;">
                    <div class="card-header">Person details
                    </div>

                    <div class="card-body card_body">
                        
                        <div class="box-primary">
                            <div class="box-header with-border">
                                <h4 class="box-title">{{$person->first_name}} {{$person->last_name}}</h4>
                            </div>
                            
                            <div class="box-body col-md-12">
                                <div class="row" style="margin-top:20px;">
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
                                            @if(!empty($disciplines) && count($disciplines)>0)
                                            <?php $step = 0; ?>
                                            @foreach($disciplines as $discipline)
                                            <?php $step++; ?>
                                            <span>one</span>,
                                            @if($step < count($disciplines))
                                                ,
                                            @endif
                                            @endforeach
                                            @else
                                            <span>None provided</span>
                                            @endif
                                    </div>
                                </div>
                                
                                @if(!empty($emails) && count($emails)>0)
                                <div class="row" style="margin-top:20px;">
                                    <h4 style="color:#777;">Emails</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <strong>Emails:</strong>
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
                                <div class="row" style="margin-top:20px;">
                                    <h6 style="color:#777;">No emails provided</h6>
                                </div>
                                @endif
                                
                                @if(!empty($addresses) && count($addresses)>0)
                                <div class="row" style="margin-top:20px;">
                                    <h4 style="color:#777;">Addresses</h4>
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
                                <div class="row" style="margin-top:20px;">
                                    <h6 style="color:#777;">No addresses provided</h6>
                                </div>
                                @endif
                                
                                
                                @if(!empty($institutions) && count($institutions)>0)
                                <div class="row" style="margin-top:20px;">
                                    <h4 style="color:#777;">Employment/Affiliations</h4>
                                </div>
                                @foreach($institutions as $institution)
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong>Institution/Company:</strong>
                                            <span>{{$institution->content}}</span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Title:</strong>
                                            <span>{{$institution->pivot->title}}</span> (<span>{{$institution->pivot->type}}</span>)
                                    </div>
                                    <div class="col-md-2">
                                        <strong>Start date:</strong>
                                            <span>{{$institution->pivot->start}}</span>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>End date:</strong>
                                            <span>{{$institution->pivot->end}}</span>
                                    </div>
                                </div>
                                <hr>
                                @endforeach
                                @else
                                <div class="row" style="margin-top:20px;">
                                    <h6 style="color:#777;">No employment/affiliation history provided</h6>
                                </div>
                                @endif
                                
                                
                                @if(!empty($degrees) && count($degrees)>0)
                                <div class="row" style="margin-top:20px;">
                                    <h4 style="color:#777;">Education</h4>
                                </div>
                                @foreach($degrees as $degree)
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Institution/Company:</strong>
                                            <span>Need institution field in degrees_persons</span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Degree:</strong>
                                            <span>{{$degree->text}}</span>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>Date:</strong>
                                            <span>{{$degree->year}}</span>
                                    </div>
                                </div>
                                <hr>
                                @endforeach
                                @else
                                <div class="row" style="margin-top:20px;">
                                    <h6 style="color:#777;">No educational history provided</h6>
                                </div>
                                @endif
                                
                            
                                @if(!empty($honors) && count($honors)>0)
                                <div class="row" style="margin-top:20px;">
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
                                <div class="row" style="margin-top:20px;">
                                    <h6 style="color:#777;">No honors provided</h6>
                                </div>
                                @endif
                                
                                
                                @if(!empty($books) && count($books)>0)
                                <div class="row" style="margin-top:20px;">
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
                                            <span>{{$book->publsher}}</span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Year:</strong>
                                            <span>{{$book->year}}</span>
                                    </div>
                                </div>
                                <hr>
                                @endforeach
                                @else
                                <div class="row" style="margin-top:20px;">
                                    <h6 style="color:#777;">No books provided</h6>
                                </div>
                                @endif


                                @if(!empty($meetings) && count($meetings)>0)
                                <div class="row" style="margin-top:20px;">
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
                                <div class="row" style="margin-top:20px;">
                                    <h6 style="color:#777;">No meeting attendance history provided</h6>
                                </div>
                                @endif


                                @if(!empty($publications) && count($publications)>0)
                                <div class="row" style="margin-top:20px;">
                                    <h4 style="color:#777;">Publications</h4>
                                </div>
                                @foreach($publications as $publication)
                                <div class="row">
                                    <div class="col-md-12">
                                        <strong>Title:</strong>
                                            <span>title</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <strong>Year:</strong>
                                            <span>date</span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Journal ref.:</strong>
                                            <span>reference</span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>ANSEF supported?:</strong>
                                            <span>yes</span>
                                    </div>
                                </div>
                                @endforeach
                                @else
                                <div class="row" style="margin-top:20px;">
                                    <h6 style="color:#777;">No publications provided</h6>
                                </div>
                                @endif
                            </div>
                        </div>            
                        <div class="col-lg-12" style="margin-top:30px;">
                            <a href="{{action('Applicant\PersonController@download', 1)}}" class="btn btn-primary">Download</a>
                            <a href="{{action('Applicant\PersonController@index') }}" class="btn btn-secondary">Go Back</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            });
    </script>
@endsection
