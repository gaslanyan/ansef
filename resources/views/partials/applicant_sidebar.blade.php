<!-- sidebar.blade.php -->
<?php
$signUser = signedApplicant();
//if(!empty(cookieSign_id()))
//    $user_id = cookieSign_id()->id;
//else
    $user_id = getUserIdByRole(null);
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image d-inline-block">
                <img src="{{asset('img/'.get_Cookie().'.png')}}" class="img-circle" alt="{{get_Cookie()}} Image"/>
            </div>
            <div class="pull-left info d-inline-block">
                <p class="">
                <?php
                if (!empty($signUser)):?>
                            <?= strtolower($signUser->email) ?>
                            <?php else: ?>
                            <?= get_Cookie(); ?>
                            <?php endif;?>
            </div>
        </div>
        <div class="figure-caption">
            <div class="line"></div>
        </div>
        <ul class="sidebar-menu" data-widget="tree">
            <li class="text-uppercase">
                <a href="{{action('Applicant\ApplicantController@index',$id='null')}}">
                    <i class="fa fa-chart-bar"></i>
                    <span>Dashboard</span>
                    <span class="pull-right-container">
                    </span>
                </a>
            </li>
            <?php if(checkUserId(null) != false ){ ?>
            <li class="text-uppercase">
                <a href="{{action('Applicant\PersonController@changePassword')}}">
                    <i class="fas fa-key"></i>
                    <span>Change Password</span>
                    <span class="pull-right-container">
                    </span>
                </a>
            </li>
            <?php }else {?>
            <li>
                Your user ID is incorrect. Contact <a href="mailto:webmaster@ansef.org">webmaster@ansef.org</a>
            </li>
            <?php } ?>
            <?php if(checkUserId(null) != false ){ ?>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-users"></i> <span>Persons</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{action('Applicant\AccountController@index')}}"><i class="fa fa-circle-o"></i>List All
                            Persons</a></li>
                    <li><a href="{{action('Applicant\AccountController@create')}}"><i class="fa fa-circle-o"></i>Add a New
                            Person</a>
                    <li><a href="{{action('Applicant\InfoController@index')}}"><i class="fa fa-circle-o"></i>Update CV
                            data for a person</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-sticky-note"></i> <span>Proposals</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{action('Applicant\ProposalController@activeProposal')}}"><i class="fa fa-circle-o"></i>Current Proposals</a></li>
                    <li><a href="{{action('Applicant\ProposalController@pastProposal')}}"><i class="fa fa-circle-o"></i>Past Proposals</a></li>
                    <li><a href="{{action('Applicant\ProposalController@create')}}"><i class="fa fa-circle-o"></i>Add A New Proposal</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                <i class="fa fa-envelope-open-text"></i> <span title="Communicate with Research Board">Communication</span>
                    <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{action('Applicant\ResearchBoardController@index','board')}}"><i class="fa fa-circle-o"></i>contact Research Board</a></li>
                    <li><a href="{{action('Applicant\ResearchBoardController@index','admin')}}"><i class="fa fa-circle-o"></i>contact Web Administrator</a></li>

                </ul>
            </li>
            <?php  }?>

        </ul>


    </section>
</aside>
