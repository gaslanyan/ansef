<div class="box-header with-border">
<h3 class="box-title"><b>Proposal {{getProposalTag($id)}}</b></h3>
</div>
<div class="box-body col-md-12">
@if(!empty($proposal))
    @if(!empty($cat_parent))
        <div class="row">
            <div class="col-md-6">
                <strong><i class="fa fa-list-alt margin-r-5"></i> Primary Category:</strong>
                <p>{{$cat_parent->title}}</p>
            </div>
            <div class="col-md-6">
                <strong><i class="fa fa-list-alt margin-r-5"></i> Primary Subcategory:</strong>
                <p>{{$cat_sub->title}}</p>
            </div>
        </div>
    @endif

    @if(!empty($cat_sec_parent))
        <div class="row">
            <div class="col-md-6">
                <strong><i class="fa fa-heading margin-r-5"></i> Secondary Category:</strong>
                <p>{{$cat_sec_parent->title}}</p>
            </div>
            <div class="col-md-6">
                <strong><i class="fa fa-heading margin-r-5"></i> Secondary Subcategory:</strong>
                <p>{{$cat_sec_sub->title}}</p>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <strong><i class="fas fa-building margin-r-5"></i> Project Institution:</strong>
            <p>{{($institution == "") ? "No institution" : $institution }}</p>
        </div>
    </div>
    <div class="row">
        @if(!empty($proposal->title))
            <div class="col-md-12">
                <strong><i class="fas fa-star margin-r-5"></i> Proposal
                    title:</strong>
                <p>{{$proposal->title}}</p>
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
    <hr>
@endif
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
                    <div class="form-group col-lg-3">
                        <strong>First Name:</strong>
                        <p>{{$person->first_name}}</p>
                    </div>
                    <div class="form-group col-lg-4">
                        <strong>Last Name:</strong>
                        <p>{{$person->last_name}}</p>
                    </div>
                    <div class="form-group col-lg-3">
                        <strong>Role: </strong>
                        <p>{{ucfirst($person->pivot->subtype == 'supportletter' ? 'recommender' : $person->pivot->subtype)}}</p>
                    </div>
                    @if(!$admin)
                    <div class="form-group col-lg-2">
                        <a href="{{action('Applicant\PersonController@show', $person->id)}}" target="_blank" class="myButton" title="View">
                            <i class="fas fa-eye">View</i>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        @endforeach
    <hr>
    @endif
    @if(!empty($budget_items) && count($budget_items) > 0)
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="fas fa-dollar-sign margin-r-5"></i><b>Budget:</b></h3>
        </div>
        <div class="col-lg-12 ">
            <label>Budget Item</label>
            @for($i = 0; $i < count($budget_items); $i++)
                <div class="row institution">
                    <div class="form-group col-lg-4">
                        <strong>Category:</strong>
                        <p>{{$budget_items[$i]->category->name}}</p>
                    </div>
                    <div class="form-group col-lg-6">
                        <strong>Description:</strong>
                        <p> {{$budget_items[$i]->description}}</p>
                    </div>

                    <div class="form-group col-lg-2">
                        <strong><i class="fas fa-dollar-sign margin-r-5"></i>Amount:</strong>
                        <p>${{$budget_items[$i]->amount}}</p>
                    </div>
                </div>
            @endfor
        </div>
    <div style="font-size: 20px; color:#555;">
        {!! $budget["summary"] !!}<br/>
    </div>
    <div style="color:#a00;">{!! $budget["validation"] !!}</div>
    <hr>
    @endif
</div>
