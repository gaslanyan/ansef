<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>Proposal {{getProposalTag($id)}}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        }
        strong {
            color: #00c0ef;
        }

    </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="container">

    <div class="row justify-content-center">
        <div class="offset-2 col-md-10">
             <div class="card" >

                <div class="card-body card_body">
                        @include('partials.status_bar')
                    <div class="card-body card_body">
                        <div class="box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title"><b>Proposal {{getProposalTag($id)}}</b></h3>
                            </div>
                            <div class="box-body col-md-12">
                                @if(!empty($proposal))
                                    @if(!empty($cat_parent))
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong><i class="fa fa-list-alt margin-r-5"></i> Primary Category:</strong>
                                                <span>{{$cat_parent->title}}</span>
                                            </div>
                                            <div class="col-md-6">
                                                <strong><i class="fa fa-list-alt margin-r-5"></i> Primary Subcategory:</strong>
                                                <span>{{$cat_sub->title}}</span>
                                            </div>
                                        </div>
                                    @endif

                                    @if(!empty($cat_sec_parent))
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong><i class="fa fa-heading margin-r-5"></i> Secondary Category:</strong>
                                                <span>{{$cat_sec_parent->title}}</span>
                                            </div>
                                            <div class="col-md-6">
                                                <strong><i class="fa fa-heading margin-r-5"></i> Secondary Subcategory:</strong>
                                                <span>{{$cat_sec_sub->title}}</span>
                                            </div>
                                        </div>
                                    @endif
                                    @if(!empty($institution))
                                        <div class="row">
                                            <div class="col-md-12">
                                                <strong><i class="fa fa-heading margin-r-5"></i> Project Institution:</strong>
                                                <span>{{!empty($institution->institution) ? $institution->institution-> content : $institution->institutionname }}</span>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="row">
                                        @if(!empty($proposal->title))
                                            <div class="col-md-12">
                                                <strong><i class="fas fa-star margin-r-5"></i> Proposal
                                                    title:</strong>
                                                <span>{{$proposal->title}}</span>
                                            </div>
                                        @endif
                                    </div>
                                @if(!empty($proposal->abstract))
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="form-group">
                                                    <strong><i class="fas fa-align-left margin-r-5"></i> Proposal
                                                        Abstract:</strong>
                                                    <p> {{$proposal->abstract}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if(!empty($proposal->document))
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="form-group">
                                                    <strong><i class="fa fa-file margin-r-5"></i> Proposal Document:</strong><br/>
                                                    <a href="\storage\proposal\prop-{{$id}}\document.pdf" target="_blank" class="btn-link">
                                                        <i class="fa fa-download"></i> Download proposal document</a>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-lg-12">
                                            <b style="color:#a00;">No proposal document uploaded</b>
                                        </div>
                                    @endif
                                @endif
                                <br/>
                                    <hr>
                                    @if(!empty($persons) && count($persons) > 0)
                                        <div class="box-header with-border">
                                            <h3 class="box-title">
                                                <i class="fas fa-user-friends margin-r-5"></i><b>Project participants:</b></h3>
                                        </div>
                                        <div>
                                            @if(!empty($pi))
                                            <br/><b style="color:#aaa;">Project Principal Investigator:</b> <b>{{$pi->first_name}} {{$pi->last_name}}</b><br/><br/>
                                            @else
                                            <br/><b style="color:#a00;">No principal investigator specified</b><br/><br/>
                                            @endif
                                        </div>
                                        @foreach($persons as $person)
                                            <div class="col-lg-12">
                                                <div class="row">
                                                    <div class="form-group col-lg-4">
                                                        <strong>First Name:</strong>
                                                        <span>{{$person->first_name}}</span>
                                                    </div>
                                                    <div class="form-group col-lg-4">
                                                        <strong>Last Name:</strong>
                                                        <span>{{$person->last_name}}</span>
                                                    </div>
                                                    <div class="form-group col-lg-4">
                                                        <strong>Role: </strong>
                                                        <span>{{$person->pivot->subtype}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <br/>
                                        @endforeach
                                    @endif
                                    <hr>
                                    @if(!empty($budget_items) && count($budget_items) > 0)
                                        <div class="box-header with-border">
                                            <h3 class="box-title">
                                                <i class="fas fa-dollar-sign margin-r-5"></i><b>Budget:</b></h3>
                                        </div>
                                        <div class="col-lg-12 ">
                                            @for($i = 0; $i < count($budget_items); $i++)
                                                <div class="row institution">
                                                    <div class="form-group col-lg-4">
                                                        <strong>Category:</strong>
                                                        <span>{{$budget_items[$i]->category->name}}</span>
                                                    </div>
                                                    <div class="form-group col-lg-6">
                                                        <strong>Description:</strong>
                                                        <span> {{$budget_items[$i]->description}}</span>
                                                    </div>

                                                    <div class="form-group col-lg-2">
                                                        <strong><i class="fas fa-dollar-sign margin-r-5"></i>Amount:</strong>
                                                        <span>${{$budget_items[$i]->amount}}</span>
                                                    </div>
                                                </div>
                                                <br/>
                                            @endfor
                                        </div>
                                    @endif
                                    <div style="font-size: 20px; color:#555;">
                                        {!! $budget["summary"] !!}<br/>
                                    </div>
                                    <div style="color:#a00;">{!! $budget["validation"] !!}</div>


                            </div>
                        </div>


                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

