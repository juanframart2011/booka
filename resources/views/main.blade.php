<!DOCTYPE html>
<html>
    @include( "helper.head" )
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            
            @include( "helper.header" )
            
            @include( "helper.menu" )
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Data Tables
                        <small>advanced tables</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="#">Tables</a></li>
                        <li class="active">Data tables</li>
                    </ol>
                </section>
                <!-- Main content -->
                <section class="content">
                    
                    @yield( "content" )
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                    <b>Version</b> 2.4.0
                </div>
                <strong>Copyright &copy; <a href="https://juanrofranco.com" target="_blank">juanrofranco.com</a>.</strong> All rights
                reserved.
            </footer>
        </div>
        <!-- ./wrapper -->
        @include( "helper.script" )
        @yield( "script_extend" )
    </body>
</html>