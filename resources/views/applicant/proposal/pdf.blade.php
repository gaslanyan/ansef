<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>{{$title}}</title>
    <style>
        strong {
            color: #00c0ef;
            font-size: large;
        }

    </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="container">

    <div class="row justify-content-center">
        <div class="offset-2 col-md-10">
             <div class="card" style="margin-top:20px;">
                <div class="card-header">Show Proposal</div>

                <div class="card-body card_body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div><br/>
                    @endif
                    @if (\Session::has('success'))
                        <div class="alert alert-success">
                            <p>@php echo html_entity_decode(\Session::get('success'), ENT_HTML5) @endphp</p>
                        </div><br/>
                    @endif
                    @if (\Session::has('wrong'))
                        <div class="alert alert-success">
                            <p>{{ \Session::get('wrong') }}</p>
                        </div><br/>
                    @endif
                    <div class="box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Proposal Information</h3>
                        </div>
                        <div class="box-body col-md-12">

                            @if(!empty($proposal))
                                <div class="row">
                                    @if(!empty($proposal->title))
                                        <div class="col-md-6">
                                            <strong><i class="fa fa-heading margin-r-5"></i> Proposal
                                                title:</strong>
                                            <p>{{$proposal->title}}</p>
                                        </div>
                                    @endif
                                    @if(!empty($competition_name))
                                        <div class="col-md-6">
                                            <strong><i class="fa fa-heading margin-r-5"></i> Competition
                                                title:</strong>
                                            <p>{{$competition_name['title']}}</p>
                                        </div>
                                    @endif


                                </div>

                                @if(!empty($cat_parent))
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong><i class="fa fa-list-alt margin-r-5"></i> Primary Category
                                                title:</strong>
                                            <p>{{$cat_parent['title']}}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <strong><i class="fa fa-list-alt margin-r-5"></i> Primary Subcategory
                                                title:</strong>
                                            <p>{{$cat_sub['title']}}</p>
                                        </div>
                                    </div>
                                @endif

                                @if(!empty($cat_sec_parent))
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong><i class="fa fa-heading margin-r-5"></i> Secondary
                                                Category:</strong>
                                            <p>{{$cat_sec_parent['title']}}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <strong><i class="fa fa-heading margin-r-5"></i> Secondary Subcategory
                                                title:</strong>
                                            <p>{{$cat_sec_sub['title']}}</p>
                                        </div>
                                    </div>
                                @endif
                                <hr>
                                @if(!empty($person_account))
                                    <div class="box-header with-border">
                                        <h3 class="box-title">
                                            <i class="fa fa-heading margin-r-5"></i> Persons
                                            Information :</h3>
                                    </div>
                                    @foreach($person_account as $pacc)
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="form-group col-lg-4">
                                                    <strong><i class="fa fa-heading margin-r-5"></i> First
                                                        Name:</strong>
                                                    <p>{{$pacc['first_name']}}</p>
                                                </div>
                                                <div class="form-group col-lg-4">
                                                    <strong><i class="fa fa-heading margin-r-5"></i> Last
                                                        Name:</strong>
                                                    <p>{{$pacc['last_name']}}</p>
                                                </div>
                                                <div class="form-group col-lg-4">
                                                    <strong><i class="fa fa-heading margin-r-5"></i> Type: </strong>
                                                    <p>{{$pacc['type']}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                                <hr>
                                @if(!empty($proposal->abstract))
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="form-group">
                                                <strong><i class="fa fa-heading margin-r-5"></i> Proposal
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
                                                <strong><i class="fa fa-heading margin-r-5"></i> Proposal
                                                    Document:</strong>
                                                <a href="storage\app\app\proposal\{{$proposal->id}}\{{$proposal->document}}"
                                                   class="btn-link col-lg-2">
                                                    <i class="fa fa-download"></i> {{$proposal->document}}</a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <hr>
                                @if(!empty($proposalreports))
                                    @foreach($proposalreports as $pr)
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="form-group">
                                                    <strong><i class="fa fa-file margin-r-5"></i> Proposal
                                                        FirstReport:</strong>
                                                    <a href="\storage\proposal\prop-{{$pr['id']}}\{{$pr['document']}}"
                                                       class="btn-link col-lg-6" target="_blank">
                                                        {{$pr['document']}} <i class="fa fa-download"></i> </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            <hr>
                                @if(!empty($budget_item))
                                    <div class="col-lg-12 ">
                                        <label>Budget Item</label>
                                        @for($i = 0; $i < count($budget_item); $i++)
                                            <div class="row institution">
                                                <div class="form-group col-lg-4">
                                                    <strong><i class="fa fa-heading margin-r-5"></i>Budget
                                                        Categories:</strong>
                                                    <p>{{$budget_categories[$i]['name']}}</p>
                                                </div>
                                                <div class="form-group col-lg-6">
                                                    <strong><i class="fa fa-heading margin-r-5"></i>Budget
                                                        Categories Description:</strong>
                                                    <p> {{$budget_item[$i]['description']}}</p>
                                                </div>

                                                <div class="form-group col-lg-2">
                                                    <strong><i class="fa fa-heading margin-r-5"></i>Amount:</strong>
                                                    <p>{{$budget_item[$i]['amount']}}</p>
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                @endif
                                <hr>
                                @if(!empty($additional))
                                    <div class="col-lg-12 ">
                                        <label>Additional Budget</label>
                                        <div class="row">
                                            <div class="form-group col-lg-6" id="additional_charge_name">
                                                <strong><i class="fa fa-plus-square margin-r-5"></i>Additional
                                                    Charge Name:</strong>
                                                <p>{{$additional->additional_charge_name}}</p>
                                            </div>
                                            <div class="form-group col-lg-6" id="additional_charge">
                                                <strong><i class="fa fa-money-bill-alt"></i>Additional
                                                    Charge:</strong>
                                                <p>{{$additional->additional_charge}}</p>

                                            </div>
                                            <div class="form-group col-lg-6" id="additional_persentage_name">
                                                <strong><i class="fa fa-plus-square  margin-r-5"></i>Additional
                                                    Persentage Name</strong>
                                                <p>{{$additional->additional_percentage_name}}</p>

                                            </div>
                                            <div class="form-group col-lg-6" id="additional_persentage">
                                                <strong><i class="fa fa-percent margin-r-5"></i>Additional
                                                    Persentage</strong>
                                                <p>{{$additional->additional_percentage}}</p>

                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <hr>
                                @if(!empty($refereereport))
                                    <div class="form-group col-lg-12">
                                        <label>Referee reports</label>
                                        @foreach($refereereport as $key => $rr)
                                            <div class="row">
                                                <div class="form-group col-lg-6">
                                                    <strong><i class="fa fa-comment-alt margin-r-5"></i>Referee #1
                                                        comments:</strong>
                                                    <p> {{$rr['public_comment']}}</p>
                                                    <strong><i class="fa fa-comment-alt margin-r-5"></i>Referee #2
                                                        comments:</strong>
                                                    <p>{{$rr['private_comment']}}</p>
                                                </div>
                                                <div class="form-group col-lg-6">
                                                    <table class="table table-bordered table-hover table-sm"
                                                           id="score_type">
                                                        <tbody>
                                                        <tr>
                                                            <th class="text-center">Category</th>
                                                            <th class="text-center">Score</th>
                                                        </tr>
                                                        <?php $scores = json_decode($rr['scores']);?>
                                                        @foreach($scores as $key=>$s)
                                                            <tr>
                                                                <td class="text-center">{{$key}}</td>
                                                                <td class="text-center">
                                                                    <?php $scores = getScoreTypeValues();
                                                                    foreach ($scores as $key => $ss){
                                                                    if($key == $s){
                                                                    ?>
                                                                    {{$ss}}
                                                                    <?php
                                                                    }
                                                                    }
                                                                    ?></td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <hr/>

                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

