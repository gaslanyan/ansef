<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>{{getProposaltag($pid)}}</title>
    <style>
        strong {
            color: #647c97;
            font-size: 14px;;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="container">
    <div class="row justify-content-center">
        <div class="offset-2 col-md-10">
                <div class="card" >
                <div class="card-body card_body" style="overflow:auto;">
                    @include('partials.status_bar')

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
                        'recommendations' => $recommendations,
                        'showdownloads' => false
                    ])

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

