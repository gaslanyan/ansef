<!-- sidebar.blade.php -->
<?php
$signUser = signUser();
$user_id = getUserId('viewer');
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image d-inline-block">
                <img src="{{asset('img/'.get_Cookie().'.png')}}" class="img-circle" alt="{{get_Cookie()}} Image"/>
            </div>
            <div class="pull-left info d-inline-block">
                <p class="text-capitalize">
                <?php
                if (!empty($signUser)):?>
                            <?= $signUser->first_name . " " .
                $signUser->last_name .
                "<br>from: " . $signUser->domain; ?>
                            <?php else: ?>
                            <?= get_Cookie(); ?>
                            <?php endif;?>
            </div>
        </div>
        <ul class="sidebar-menu" data-widget="tree">
            <li class="text-uppercase">
                <a href="{{action('Viewer\ViewerController@index')}}">
                    <i class="fa fa-chart-bar"></i>
                    <span>Dashboard</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-user"></i> <span>Your Profile</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <?php if(checkUserId('viewer') != false ){ ?>
                    <li><a href="{{action('Viewer\PersonController@create')}}"><i class="fa fa-circle-o"></i>Add account</a>
                    </li>
                    <?php }else{?>
                    <li><a href="{{action('Viewer\PersonController@edit', $user_id)}}"><i class="fa fa-circle-o"></i>Edit your Profile</a></li>
                    <?php } ?>
                    {{--<li><a href=""><i class="fa fa-circle-o"></i>Delete Profile</a></li>--}}
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-book"></i> <span>Proposals</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{action('Viewer\ProposalController@index')}}"><i class="fa fa-circle-o"></i>Show All Proposals</a></li>
                    {{--<li><a href="{{action('Base\Books\BookController@create')}}"><i class="fa fa-circle-o"></i>Add Book</a>--}}
                    {{--</li>--}}

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