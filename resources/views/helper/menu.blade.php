<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset( "img/user2-160x160.jpg" ) }}" class="img-circle" alt="{{ session( "us3r-email" ) }}">
            </div>
            <div class="pull-left info">
                <p>{{ session( "us3r-email" ) }}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MENU</li>
            <li class="treeview {{ ( @$menu_user )? 'active' : '' }}">
                <a href="{{ route( 'home' ) }}"><i class="fa fa-users"></i> <span>Usuario</span></a>
            </li>
            <li class="treeview {{ ( @$menu_book )? 'active' : '' }} {{ ( @$menu_book_open )? 'menu-open' : '' }}">
                <a href="#"><i class="fa fa-book"></i> <span>Book</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                <ul class="treeview-menu">
                    <li>
                        <a href=""><i class="fa fa-circle-o"></i> Lista de Book</a>
                    </li>
                    <li>
                        <a href=""><i class="fa fa-circle-o"></i> Prestar Libro</a>
                    </li>
                </ul>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>