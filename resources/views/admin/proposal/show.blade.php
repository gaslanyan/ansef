@extends('layouts.master')
@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" >
                    <div class="card-header">Proposal

                        <a href="{{ \Illuminate\Support\Facades\URL::previous() }}"
                           class="display float-lg-right btn-box-tool"> Back</a>
                        <a href="{{ action('Admin\ProposalController@create') }}"
                           class="display float-lg-right btn-primary px-2"> Add or edit comment</a>
                    </div>
                    <div class="card-body card_body">
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

                        <div class="box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Proposal information</h3>

                            </div>
                            <div class="box-body col-md-12">
                                @if(!empty($proposal))
                                    @php
                                    echo $proposal['id'];
                                    \Illuminate\Support\Facades\Session::put('p_id', $proposal['id']);
                                    @endphp
                                    <div class="row">
                                        @if(!empty($proposal['title']))
                                            <div class="col-md-6">
                                                pull  <strong><i class="fa fa-heading margin-r-5"></i> Proposal
                                                    title:</strong>
                                                <p>{{$proposal['title']}}</p>
                                            </div>
                                        @endif
                                        @if(!empty($proposal['competition']['title']))
                                            <div class="col-md-6">
                                                <strong><i class="fa fa-heading margin-r-5"> </i>Competition
                                                    title:</strong>
                                                <p>{{$proposal['competition']['title']}}</p>
                                            </div>
                                        @endif
                                        @if(!empty($proposal['document']))
                                            <div class="col-md-6">
                                                <strong><i class="fa fa-download margin-r-5"></i> Document file
                                                    present:</strong>
                                                @if(is_file(storage_path('proposal/prop-'.$proposal['id'].'/'.$proposal->document)))
                                                <p><a href="{{'\storage\proposal\prop-'.$proposal['id'].'/'.$proposal->document}}" download>Download document</a></p>
                                                    @endif
                                            </div>
                                        @endif
                                        @if(!empty($proposal['abstract']))
                                            <div class="col-md-6">
                                                <strong><i class="fas fa-question-circle margin-r-5"></i> Proposal
                                                    abstract:</strong>
                                                <p>{{$proposal['abstract']}}</p>
                                            </div>
                                        @endif
                                        @if(!empty($cats))
                                            @php $step = 0; @endphp
                                            @foreach ($cats as $index => $cat)
                                                <div class="col-md-6">
                                                    <strong>
                                                        <i class="fa fa-list-alt margin-r-5"></i>
                                                        @if($step == 0){{'Primary'}}@else{{'Secondary'}} @endif
                                                        category:</strong>
                                                    @php $step ++; @endphp
                                                    <p>{{$cat['parent']}}</p>
                                                    @if(isset($cat['sub']))
                                                        <ul>
                                                            <li>{{$cat['sub']}}</li>
                                                        </ul>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @endif
                                        @if(!empty($proposal['comment']))
                                            <div class="col-md-6">
                                                <strong><i class="fa fa-comment margin-r-5"></i>
                                                    Competition
                                                    comment:</strong>
                                                <p>{{$proposal['comment']}}</p>
                                            </div>
                                        @endif
                                        @if(!empty($proposal['state']))
                                            <div class="col-md-4">
                                                <strong><i class="fa fa-bookmark margin-r-5"></i>
                                                    Proposal
                                                    State:</strong>
                                                <p>{{$proposal['state']}}</p>
                                            </div>
                                        @endif
                                        <div class="box-header with-border col-12">
                                            <h3 class="box-title">Project PI and Collaborators</h3>
                                        </div>
                                        @php
                                            $accounts = json_decode($proposal->proposal_members);
                                               if($accounts->person_pi_id){
                                                 $pi = getUser($accounts->person_pi_id);
                                               echo printUser($pi,$accounts);
                                               }
                                        @endphp
                                        @if(!empty($proposal->proposal_referees))
                                            <div class="box-header with-border col-12">
                                                <h3 class="box-title">Proposals referees</h3>
                                            </div>
                                            @if(!empty($referee_info))
                                                @foreach ($referee_info as $index => $ref)
                                                    <div class="col-md-12">
                                                        <strong><i class="fa fa-user-check margin-r-5"></i>
                                                            Referee Info :</strong>
                                                        <a target="_blank" href="{{action(ucfirst('referee').'\\'.ucfirst('referee').'Controller@index',
                                                          $ref['id'])}}">
                                                            <strong>
                                                                @if(isset($ref['first_name'])){{$ref['first_name']}}@endif
                                                                @if(isset($ref['last_name'])){{" ".$ref['last_name']}}@endif
                                                            </strong>
                                                        </a>
                                                    </div>
                                                    <div class="col-md-6">
                                                        @if(isset($ref['public_comment']))
                                                            <div class="col-md-12">
                                                                <strong><i class="fa fa-comment margin-r-5"></i>
                                                                    Public Comment:</strong>
                                                                <p>{{" ".$ref['public_comment']}}</p>
                                                            </div>
                                                        @endif
                                                        @if(isset($ref['private_comment']))
                                                            <div class="col-md-12">
                                                                <strong><i class="fa fa-comment margin-r-5"></i> Private
                                                                    Comment:</strong>
                                                                <p>{{" ".$ref['private_comment']}}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-6">
                                                        @if(isset($ref['dur_date']))
                                                            <div class="col-md-12">
                                                                <strong><i class="fa fa-calendar margin-r-5"></i>
                                                                    Dur Date:</strong>
                                                                <p>{{" ".$ref['dur_date']}}</p>
                                                            </div>
                                                        @endif
                                                        @if(isset($ref['overall_scope']))
                                                            <div class="col-md-12">
                                                                <strong><i class="fa fa-calculator margin-r-5"></i>
                                                                    Overall Scope:</strong>
                                                                <p>{{" ".$ref['overall_scope']}}</p>
                                                            </div>
                                                        @endif

                                                    </div>
                                                    <hr class="col-lg-12">
                                                @endforeach
                                            @endif


                                        @endif
                                            @if(!empty($scores))
                                                <div class="col-md-12">
                                                    <strong><i class="fa fa-list-alt margin-r-5"></i>
                                                        Scores:</strong>
                                                    @php
                                                        $stv = getScoreTypeValues();
                                                    @endphp
                                                    @foreach ($scores as $index => $score)
                                                        <div class="row">
                                                            <span class="col-md-6">{{$score->name}}</span>
                                                            <span class="col-md-6">{{$stv[$score->value]}}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                    </div>
                            </div>
                            @else
                                <p>Can't find data</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
