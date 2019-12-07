@extends('layouts.master')
@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" >
                    <div class="card-header">Proposal
                         <a href="{{action('Viewer\ProposalController@generatePDF',$proposal['id'])}}"
                            title="Download"
                            class="add_honors float-right"><i class="fa fa-download"></i>
                         </a>
                    </div>
                    <div class="card-body card_body" style="overflow:auto;">
                        @include('partials.status_bar')

                        <div class="box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Proposal information</h3>
                            </div>
                            <div class="box-body col-md-12">
                                <div class="row">

                                    <div class="col-md-6">
                                        <strong><i class="fa fa-calendar margin-r-5"></i>Submission start
                                            date:</strong>
                                        <p>{{$competitions->submission_start_date}}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong><i class="fas fa-question-circle margin-r-5"></i>Competition Title:</strong>
                                        <p>{{$competitions->title}}</p>
                                    </div>
                                </div>
                                <hr>
                            </div>

                            <div class="box-body col-md-12">
                                <div class="row">

                                    <div class="col-md-6">
                                        <strong><i class="fas fa-question-circle margin-r-5"></i>Category</strong>
                                        <p>{{$cat_parent->title}}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong><i class="fas fa-question-circle margin-r-5"></i> SubCategory:</strong>
                                        <p>{{$cat_sub->title}}</p>
                                    </div>
                                </div>
                                <hr>
                            </div>

                            <div class="box-body col-md-12">
                                <div class="row">

                                    <div class="col-md-6">
                                        <strong><i class="fas fa-question-circle margin-r-5"></i>Secondary Category:
                                            date:</strong>
                                        <p>{{$cat_sec_parent->title}}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong><i class="fas fa-question-circle margin-r-5"></i>Secondary SubCategory:</strong>
                                        <p>{{$cat_sec_sub->title}}</p>
                                    </div>
                                </div>
                                <hr>
                            </div>

                            <div class="box-body col-md-12">
                                <div class="row">

                                    <div class="col-md-12">
                                        <strong><i class="fas fa-question-circle margin-r-5"></i>Proposal Title:
                                            date:</strong>
                                        <p>{{$proposal->title}}</p>
                                    </div>
                                </div>
                                <hr>
                            </div>

                            <div class="box-body col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <strong><i class="fas fa-question-circle margin-r-5"></i>Proposal Abstract:</strong>
                                        <p>{{$proposal->abstract}}</p>
                                    </div>
                                </div>
                                <hr>
                            </div>
                            <div class="box-body col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <strong><i class="fas fa-question-circle margin-r-5"></i>Proposal Document:</strong>
                                        <p><a href="{{ url('/proposal/my.csv')  }}">{{$proposal->document}}</a></p>
                                    </div>
                                </div>
                                <hr>
                            </div>
                            <div class="box-body col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <strong><i class="fas fa-question-circle margin-r-5"></i>Proposal Reports:</strong>
                                        @foreach($proposalreports as $prop_rep)
                                            <p><a href="">{{$prop_rep['document']}}</a></p>
                                        @endforeach
                                    </div>
                                </div>
                                <hr>
                            </div>


                            @if(!empty($person_account))
                                <div class="box-body col-md-12">
                                    <div class="col-md-12">
                                        <strong><i class="fa fa-user-friends margin-r-5"></i>Proposal Members:</strong>
                                    </div>
                                    @foreach($person_account as $pacc)
                                        <div class="col-lg-12">
                                            <div class="row">

                                                <div class="form-group col-lg-4">
                                                    <input type="text" class="form-control" name="person_members[]"
                                                           id="" value="{{$pacc['first_name']}}">
                                                </div>
                                                <div class="form-group col-lg-4">
                                                    <input type="text" class="form-control" name="person_members[]"
                                                           id="" value="{{$pacc['last_name']}}">
                                                </div>
                                                <div class="form-group col-lg-2">
                                                    <input type="text" class="form-control" name="person_members[]"
                                                           id="" value="{{$pacc['type']}}">
                                                </div>
                                                <div class="form-group col-lg-2">
                                                    <a href="{{action('Viewer\PersonController@show', $pacc['id'])}}"
                                                       title="View" class="btn-link full_edit"><i
                                                                class="fa fa-eye"></i> </a>
                                                </div>
                                            </div>

                                        </div>

                                    @endforeach
                                </div>
                            @endif

                            @if(!empty($budget_item))
                                <div class="box-body col-md-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <strong><i class="fa fa-dollar margin-r-5"></i>Budget (total amount):
                                            </strong>
                                            <div class="col-lg-12">
                                                <div class="row">
                                                    <div class="form-group col-lg-3">
                                                        <strong><i class="fa fa-dollar margin-r-5"></i>Budget Type:
                                                        </strong>
                                                    </div>
                                                    <div class="form-group col-lg-6">
                                                        <strong><i class="fa fa-dollar margin-r-5"></i>Budget
                                                            Descriptiom:
                                                        </strong>
                                                    </div>
                                                    <div class="form-group col-lg-3">
                                                        <strong><i class="fa fa-dollar margin-r-5"></i>Amount Type:
                                                        </strong>
                                                    </div>
                                                </div>
                                            </div>
                                            @foreach($budget_item as $bg_item)
                                                <div class="col-lg-12">
                                                    <div class="row">
                                                        <div class="form-group col-lg-3">
                                                            <input type="text" class="form-control"
                                                                   name="person_members[]"
                                                                   id="" value="{{$bg_item->name}}">
                                                        </div>
                                                        <div class="form-group col-lg-6">
                                                            <input type="text" class="form-control"
                                                                   name="person_members[]"
                                                                   id="" value="{{$bg_item->description}}">
                                                        </div>
                                                        <div class="form-group col-lg-3">
                                                            <input type="text" class="form-control"
                                                                   name="person_members[]"
                                                                   id="" value="{{$bg_item->amount}}">
                                                        </div>
                                                    </div>

                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                            @endif;

                        </div>
                    </div>
                </div>

@endsection

