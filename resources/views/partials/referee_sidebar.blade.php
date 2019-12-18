<!-- sidebar.blade.php -->
<?php
$signUser = loggedApplicant();
$user_id = getPersonIdByRole('referee');
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
        <div class="figure-caption">
            <div class="line"></div>
        </div>
        <ul class="sidebar-menu" data-widget="tree">
            <li class="text-uppercase">
                <a href="{{action('Referee\RefereeController@index',$id='null')}}">
                    <i class="fa fa-chart-bar"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <?php if(userHasPerson()){ ?>
            <li class="text-uppercase">
                <a href="{{action('Referee\PersonController@changePassword')}}">
                    <i class="fas fa-key"></i>
                    <span>Change Password</span>
                    <span class="pull-right-container">
                    </span>
                </a>
            </li>
            <li class="text-uppercase">
                <a href="{{action('Referee\PersonController@edit', $user_id)}}">
                    <i class="fa fa-user"></i>
                    <span>Update your profile</span>
                    <span class="pull-right-container">
                    </span>
                </a>
            </li>
            <?php }else {?>
            <li>
                Your user ID is incorrect. Contact <a href="mailto:webmaster@ansef.org">webmaster@ansef.org</a>
            </li>
            <?php } ?>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-sticky-note"></i>
                    <span>Reports</span>
                    <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="{{action('Referee\ReportController@state', 'in-progress')}}">
                            <i class="fa fa-circle-o"></i>
                            Current Reports
                        </a>
                    </li>
                    <li>
                        <a href="{{action('Referee\ReportController@state', 'complete')}}">
                            <i class="fa fa-circle-o"></i>
                            Past Reports
                        </a>
                    </li>
                </ul>
            </li>

        </ul>


    </section>
</aside>
