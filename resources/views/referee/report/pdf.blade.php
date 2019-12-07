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
        <div class="offset-md-2 col-md-10">
             <div class="card" >
                <div class="card-header">Report about proposal</div>

                <div class="card-body card_body" style="overflow:auto;">
                        @include('partials.status_bar')


                    <div class="box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Report and Proposal information</h3>
                        </div>
                        <div class="box-body col-md-12">

                            @if(!empty($report))

                                <div class="row">
                                    @if(!empty($report->proposal->title))
                                        <div class="col-md-6">
                                            <strong><i class="fa fa-heading margin-r-5"></i> Proposal
                                                title:</strong>
                                            <p>{{$report->proposal->title}}</p>
                                        </div>
                                    @endif

                                    @if(!empty($report->proposal->competition->title))
                                        <div class="col-md-6">
                                            <strong><i class="fa fa-heading margin-r-5"> </i>
                                                Competition title:</strong>
                                            <p>{{$report->proposal->competition->title}}</p>
                                        </div>
                                    @endif
                                    @if(!empty($report->proposal->document))
                                        <div class="col-md-6">
                                            <strong><i class="fa fa-download margin-r-5"></i> Document file
                                                present:</strong>
                                            <p><a href="{{$report->proposal->document}}">Download document</a></p>
                                        </div>
                                    @endif

                                    @if(!empty($report->proposal->abstract))
                                        <div class="col-md-6">
                                            <strong><i class="fas fa-question-circle margin-r-5"></i> Proposal
                                                abstract:</strong>
                                            <p>{{$report->proposal->abstract}}</p>
                                        </div>
                                    @endif
                                    <div class="box-header with-border col-12">
                                        <h3 class="box-title">
                                            Categories</h3>
                                    </div>
                                    @if(!empty($cats))
                                        @php
                                            $step = 0; @endphp
                                        @foreach ($cats as $index => $cat)
                                            @if($step == 0)
                                                <div class="col-md-6">
                                                    <strong><i class="fa fa-list-alt margin-r-5"></i> Primary
                                                        category:</strong>
                                                    @else
                                                        <div class="col-md-6">
                                                            <strong><i class="fa fa-list-alt margin-r-5"></i>
                                                                Secondary
                                                                category:</strong>
                                                            @endif
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
                                                    <div class="box-header with-border col-12">
                                                        <h3 class="box-title">
                                                            Referee comments</h3>
                                                    </div>
                                                    @if(!empty($report->public_comment))
                                                        <div class="col-md-6">
                                                            <strong><i class="fas fa-question-circle margin-r-5"></i> Proposal
                                                                public comment:</strong>
                                                            <p>{{$report->public_comment}}</p>
                                                        </div>
                                                    @endif
                                                    @if(!empty($report->private_comment))
                                                        <div class="col-md-6">
                                                            <strong><i class="fas fa-question-circle margin-r-5"></i> Proposal
                                                                private comment:</strong>
                                                            <p>{{$report->private_comment}}</p>
                                                        </div>
                                                    @endif

                                                    @if(!empty($report->proposal->comments))
                                                        <div class="col-md-6">
                                                            <strong><i class="fa fa-comment margin-r-5"></i>
                                                                Competition
                                                                comment:</strong>
                                                            <p>{{$report->proposal->comments}}</p>
                                                        </div>
                                                    @endif
                                                    @if(!empty($report->proposal->state))
                                                        <div class="col-md-4">
                                                            <strong><i class="fa fa-bookmark margin-r-5"></i>
                                                                Proposal
                                                                State:</strong>
                                                            <p>{{$report->proposal->state}}</p>
                                                        </div>
                                                    @endif
                                                    <div class="box-header with-border col-12">
                                                        <h3 class="box-title">
                                                            Project PI and Collaborators</h3>
                                                    </div>
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
</div>
</body>
</html>

