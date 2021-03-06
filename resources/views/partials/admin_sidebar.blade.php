<!-- sidebar.blade.php -->
<?php
$signedPerson = loggedPerson();
$user_id = getUserID();
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left d-inline-block">
                <p class="" style="color:#999;">
                <?php if (!empty($signUser)):?>
                    <?= "Logged in as <b>" . $signUser->first_name . " " .
                $signUser->last_name . "</b><br>(" . \App\Models\User::find($user_id)->email .")"; ?>
                <?php else: ?>
                    <?= get_role_cookie(); ?>
                <?php endif;?>
            </div>
        </div>
        <ul class="sidebar-menu" data-widget="tree">
            <li class="text-uppercase">
                <a href="/admin">
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
                        <a href="{{action('Admin\AccountController@index')}}">
                            Accounts</a>
                    </li>
                    <li>
                        <a href="{{action('Admin\AccountController@account',['subtype' => 'PI', 'type' => 'applicant'])}}">
                            List of PIs</a>
                    </li>
                    <li>
                        <a href="{{action('Admin\AccountController@account',['subtype' => 'collaborator', 'type' => 'applicant'])}}">
                            List of Collaborators</a>
                    </li>
                    <li>
                        <a href="{{action('Admin\AccountController@account',['subtype' => 'none', 'type' => 'referee'])}}">
                            List of referees</a>
                    </li>
                    <li>
                        <a href="{{action('Admin\AccountController@create')}}">
                            Add a person</a>
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
                    <li class="text-uppercase">
                        <a href="{{action('Admin\PersonController@edit', $user_id)}}">
                            <i class="fa fa-user"></i>
                            <span>Update your profile</span>
                            <span class="pull-right-container">
                            </span>
                        </a>
                    </li>
                    <li><a href="{{action('Admin\PersonController@changePassword')}}"><i class="fa fa-circle-o"></i>Change your Password</a></li>
                </ul>
            </li>
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
                        @if(get_role_cookie() === "superadmin")
                    <li><a href="{{action('Admin\CategoryController@create')}}"><i class="fa fa-circle-o"></i>Add
                            a category</a></li>
                        @endif
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
                    <li><a href="{{action('Admin\DegreeController@index')}}"><i class="fa fa-circle-o"></i>Show degrees</a>
                    </li>
                        @if(get_role_cookie() === "superadmin")
                    <li><a href="{{action('Admin\DegreeController@create')}}"><i class="fa fa-circle-o"></i>Add
                            a degree</a></li>
                        @endif
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
                        <a href="{{action('Admin\RankingRuleController@rankingslist')}}"><i class="fa fa-circle-o"></i>Ranking
                            rules
                        </a>
                    </li>

                </ul>
            </li>
            {{--            <div class="line"></div>--}}


            <li class="treeview">
                <a href="#">
                    <i class="fa fa-sticky-note"></i>
                    <span>Proposals</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{action('Admin\ProposalController@list')}}"><i class="fa fa-circle-o"></i>
                        Show proposals</a></li>
                    <li><a href="{{action('Admin\ProposalController@awardslist')}}"><i class="fa fa-circle-o"></i>
                        Show awards</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-chart-bar"></i>
                    <span>Reports</span>
                    <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{action('Admin\ReportController@list')}}"><i class="fa fa-circle-o"></i>Show
                             referee reports</a></li>
                    {{-- <li><a href="{{action('Admin\ReportController@approve')}}"><i class="fa fa-circle-o"></i>Show PI reports</a></li> --}}

                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-mail-bulk"></i> <span>Send emails</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="{{action('Admin\InvitationController@send')}}">
                            <i class="fa fa-circle-o"></i>Send emails</a>
                    </li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-home"></i> <span>Institutions</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{action('Admin\InstitutionController@index')}}"><i class="fa fa-circle-o"></i>List of
                            institutions</a></li>
                        @if(get_role_cookie() === "superadmin")
                    <li><a href="{{action('Admin\InstitutionController@create')}}"><i class="fa fa-circle-o"></i>Add
                            an institution</a></li>
                        @endif
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-comments"></i> <span>Email Templates</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{action('Admin\MessageController@index')}}"><i class="fa fa-circle-o"></i>Show
                            templates</a></li>
                        @if(get_role_cookie() === "superadmin")
                    <li><a href="{{action('Admin\MessageController@create')}}"><i class="fa fa-circle-o"></i>Add
                            a template</a></li>
                        @endif
                </ul>
            </li>
            @if(get_role_cookie() == 'superadmin')
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-comments"></i> <span>Auto. Messages</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{action('Admin\TemplateController@index')}}"><i class="fa fa-circle-o"></i>Show
                            messages</a></li>
                        @if(get_role_cookie() === "superadmin")
                    <li><a href="{{action('Admin\TemplateController@create')}}"><i class="fa fa-circle-o"></i>Add
                            a message</a></li>
                        @endif
            <li class="text-uppercase">
                <a href="{{action('Viewer\StatisticsController@index')}}">
                    <i class="fa fa-chart-bar"></i>
                    <span>Statistics</span>
                    </span>
                </a>
            </li>
                </ul>
            </li>
            @endif
        </ul>
        <div class="line"></div>
        @if(get_role_cookie() === "superadmin")
        <ul class="sidebar-menu" data-widget="tree">
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-eye-slash"></i>
                    <span>Toggle Portal</span>
                    <?php $content = \Illuminate\Support\Facades\Storage::disk('local')->get('lock.json');
                    $content = json_decode($content);

                    ?>
                    <span class="pull-right-container">
                    <i class="fa  fa-toggle-off" id="off"
                       style="display:<?= (!empty($content) && $content->lock === 'on') ? 'block' : 'none'?>"></i>
                    <i class="fa fa-toggle-on"
                       style="display: <?= (!empty($content) && $content->lock === 'off') ? 'block' : 'none'?>"
                       id="on"></i>
                    </span>
                </a>
            </li>
            <li class="">
                <a href="{{action('Admin\SettingsController@sql')}}">
                    <i class="fa fa-database"></i>
                    <span>Backup database</span>
                </a>
            </li>
            <li class="">
                <a href="{{action('Admin\SettingsController@exportForm')}}">
                    <i class="fa fa-database"></i>
                    <span>Export database tables</span>
                </a>
            </li>
        </ul>
        @endif
    </section>
</aside>
