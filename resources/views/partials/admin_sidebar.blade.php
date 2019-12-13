<!-- sidebar.blade.php -->
<?php $signUser = signPerson();
$u_id = \Illuminate\Support\Facades\Session::get('u_id');
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image d-inline-block">
                <img src="{{asset('img/'.get_role_cookie().'.png')}}" class="img-circle" alt="{{get_role_cookie()}} Image"/>
            </div>
            <div class="pull-left info d-inline-block">
                <p class="text-capitalize">
                    <?php
                    if (!empty($signUser)):?>
                            <?= $signUser->first_name . " " .
                    $signUser->last_name .
                    "<br>from: " . $signUser->domain; ?>
                            <?php else: ?>
                            <?= get_role_cookie(); ?>
                            <?php endif;?>
                </p>
            </div>
        </div>
        <ul class="sidebar-menu" data-widget="tree">
            <li class="text-uppercase">
                <a href="{{action('Admin\AdminController@index')}}">
                    <i class="fa fa-chart-bar"></i>
                    <span>Dashboard</span>

                </a>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-users"></i>
                    <span>Accounts</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="{{action('Admin\PersonController@index')}}"><i class="fa fa-circle-o"></i>List of
                            all users</a>
                    </li>
                    <li>
                        <a href="{{action('Admin\AccountController@account','applicant')}}"><i
                                    class="fa fa-circle-o"></i>List of
                            applicants</a>
                    </li>
                    <li>
                        <a href="{{action('Admin\AccountController@account','referee')}}"><i class="fa fa-circle-o"></i>List
                            of
                            referees</a>
                    </li>
                    <li>
                        <a href="{{action('Admin\AccountController@account','viewer')}}"><i class="fa fa-circle-o"></i>List
                            of
                            viewers</a>
                    </li>
                    <li>
                        <a href="{{action('Admin\AccountController@account','admin')}}"><i class="fa fa-circle-o"></i>List
                            of
                            administrators</a>
                    </li>

                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-user"></i>
                    <span>Your Profile</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="{{action('Admin\PersonController@edit', personidforuser($u_id))}}"><i class="fa fa-circle-o"></i>Edit
                            your Profile</a></li>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa"></i>
                            <span>Phones/Emails</span>
                            <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{action('Admin\PhoneController@index')}}"><i class="fa fa-circle-o"></i>Show
                                    phone numbers</a></li>
                            <li><a href="{{action('Admin\EmailController@index')}}"><i class="fa fa-circle-o"></i>Show
                                    emails</a></li>
                        </ul>
                    </li>

                    <li><a href="{{action('Admin\PersonController@changePassword')}}"><i class="fa fa-circle-o"></i>Change
                            your Password</a></li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-trophy"></i> <span>Competition</span>
                    <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{action('Admin\CompetitionController@index')}}"><i class="fa fa-circle-o"></i>Competitions</a>
                    </li>
                    <li>
                        <a href="{{action('Admin\BudgetCategoryController@index')}}"><i class="fa fa-circle-o"></i>Budget
                            categories</a>
                    </li>
                    <li>
                        <a href="{{action('Admin\ScoreTypeController@index')}}"><i class="fa fa-circle-o"></i>Score
                            types</a>
                    </li>
                    <li>
                        <a href="{{action('Admin\RankingRuleController@index')}}"><i class="fa fa-circle-o"></i>Ranking
                            rules
                        </a>
                    </li>
                </ul>
            </li>
            {{--            <div class="line"></div>--}}
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-list-alt"></i> <span>Categories</span>
                    <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{action('Admin\CategoryController@index')}}"><i class="fa fa-circle-o"></i>Show
                            categories</a></li>

                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-graduation-cap"></i> <span>Degrees</span>
                    <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{action('Admin\DegreeController@index')}}"><i class="fa fa-circle-o"></i>Show Degrees</a>
                    </li>
                    <li><a href="{{action('Admin\DegreeController@create')}}"><i class="fa fa-circle-o"></i>Add
                            a degree</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-sticky-note"></i>
                    <span>Proposals</span>
                    <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{action('Admin\ProposalController@index')}}"><i class="fa fa-circle-o"></i>Show
                            Proposals</a></li>

                </ul>
            </li>
            {{--<li class="treeview">--}}
                {{--<a href="#">--}}
                    {{--<i class="fa fa-mail-bulk"></i> <span>Send Emails</span>--}}
                    {{--<span class="pull-right-container">--}}
                    {{--<i class="fa fa-angle-left pull-right"></i>--}}
                    {{--</span>--}}
                {{--</a>--}}
                {{--<ul class="treeview-menu">--}}
                    {{--<li>--}}
                        {{--<a href="{{action('Admin\InvitationController@create')}}">--}}
                            {{--<i class="fa fa-circle-o"></i>Add an invitation</a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                        {{--<a href="{{action('Admin\InvitationController@send')}}">--}}
                            {{--<i class="fa fa-circle-o"></i>Send email(s)</a>--}}
                    {{--</li>--}}
                {{--</ul>--}}
            {{--</li>--}}
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-home"></i> <span>Institution</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{action('Admin\InstitutionController@index')}}"><i class="fa fa-circle-o"></i>List of
                            institutions</a></li>
                    <li><a href="{{action('Admin\InstitutionController@create')}}"><i class="fa fa-circle-o"></i>Add
                            an institution</a></li>
                </ul>
            </li>

        </ul>
        <div class="line"></div>

    </section>
</aside>
