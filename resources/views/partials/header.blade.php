<!-- header.blade.php -->
<?php $signedUser = loggedPerson();?>
<header class="main-header">
    <a href="" class="logo">
        <span class="logo-mini"><b>Ansef</b></span>
        <span class="logo-lg"><b>ANSEF</b></span>
    </a>
    <nav class="navbar navbar-static-top">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
            <i class="fa fa-th margin-r-5"></i>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" id="dLabel" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                       aria-expanded="false">

                        <span class="hidden-xs">
                            <?= ucwords(get_role_cookie()) . " logout"; ?>
                        </span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dLabel">
                        <li class="user-footer">
                            <div class="pull-right">
                                <a class="dropdown-item btn btn-default" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

</header>
