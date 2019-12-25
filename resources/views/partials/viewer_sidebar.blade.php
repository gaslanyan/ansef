<!-- sidebar.blade.php -->
<?php
$signUser = loggedApplicant();
$user_id = getPersonIdByRole('viewer');
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left d-inline-block">
                <p class="" style="colo:#999;">
                <?php if (!empty($signUser)):?>
                    <?= "Logged in as <b>" . $signUser->first_name . " " .
                $signUser->last_name . "</b>"; ?>
                <?php else: ?>
                    <?= get_role_cookie(); ?>
                <?php endif;?>
            </div>
        </div>
        <ul class="sidebar-menu" data-widget="tree">
            <li class="text-uppercase">
                <a href="/viewer">
                    <i class="fa fa-chart-bar"></i>
                    <span>Dashboard</span>

                </a>
            </li>
            <?php if(userHasPerson()){ ?>
            <li class="text-uppercase">
                <a href="{{action('Viewer\PersonController@changePassword')}}">
                    <i class="fas fa-key"></i>
                    <span>Change Password</span>
                    <span class="pull-right-container">
                    </span>
                </a>
            </li>
            <li class="text-uppercase">
                <a href="{{action('Viewer\PersonController@edit', $user_id)}}">
                    <i class="fa fa-user"></i>
                    <span>Update your profile</span>
                    <span class="pull-right-container">
                    </span>
                </a>
            </li>
            <?php }else {?>
            <li>
                Your user ID is incorrect. Contact <a href="mailto:{{config('emails.webmaster')}}">{{config('emails.webmaster')}}</a>
            </li>
            <?php } ?>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-book"></i> <span>Proposals</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{action('Viewer\ProposalController@awardslist', empty(\App\Models\Competition::latest('created_at')->first()) ? -1 : \App\Models\Competition::latest('created_at')->first()->id)}}"><i class="fa fa-circle-o"></i>
                        Show awards</a></li>
                </ul>
            </li>

            <li class="text-uppercase">
                <a href="{{action('Viewer\StatisticsController@index')}}">
                    <i class="fa fa-chart-bar"></i>
                    <span>Statistics</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
            </li>
        </ul>
    </section>
</aside>
