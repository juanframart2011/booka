<header class="main-header">
    <!-- Logo -->
    <a href="{{ route( "login" ) }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>B</b>A</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>Book</b>A</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="{{ asset( "img/user2-160x160.jpg" ) }}" class="user-image" alt="{{ session( "us3r-email" ) }}"><span class="hidden-xs">{{ session( "us3r-email" ) }}</span></a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="{{ asset( "img/user2-160x160.jpg" ) }}" class="img-circle" alt="{{ session( "us3r-email" ) }}">
                            <p>
                                {{ session( "us3r-name" ) . ' ' . session( "us3r-lastName" ) . ' - ' . session( "us3r-rol_name" ) }}
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{ route( 'user' ) . '/?id=' . session( "us3r-encrypted" ) }}" class="btn btn-default btn-flat">Perfil</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{ route( 'logout' ) }}" class="btn btn-default btn-flat">Salir</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>