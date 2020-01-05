<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Proposal {{getProposalTag($id)}}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        }

        strong {
            color: #647c97;
            font-size: 14px;
            ;
        }

    </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body" style="overflow:auto;">
                        <h5>Proposal {{getProposalTag($id)}}</h5>
                        @if($proposal->competition->results_date < date('Y-m-d'))
                            @include('partials.refereereports',[ 'reports'=> $reports,
                            'private' => false
                            ])
                            @endif

                            @include('partials.proposal',[
                            'pid' => $pid,
                            'proposal' => $proposal,
                            'cat_parent' => $cat_parent,
                            'cat_sub' => $cat_sub,
                            'cat_sec_parent' => $cat_sec_parent,
                            'cat_sec_sub' => $cat_sec_sub,
                            'institution' => $institution,
                            'persons' => $persons,
                            'pi' => $pi,
                            'budget_items' => $budget_items,
                            'budget' => $budget,
                            'showdownloads' => false
                            ])

                            @include('partials.proposaldetails',[
                            'persons' => $persons,
                            'recommendations' => null,
                            'showdownloads' => false
                            ])
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
