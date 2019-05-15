<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Booka | Login</title>

        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.7 -->
        <link rel="stylesheet" href="{{ asset( 'css/plugin/bootstrap.min.css' ) }}">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ asset( 'fonts/font-awesome/css/font-awesome.min.css' ) }}">
        <!-- Ionicons -->
        <link rel="stylesheet" href="{{ asset( 'fonts/Ionicons/css/ionicons.min.css' ) }}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ asset( 'css/AdminLTE.min.css' ) }}">
        <!-- iCheck -->
        <link rel="stylesheet" href="{{ asset( 'plugins/iCheck/square/blue.css' ) }}">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>
    <body class="hold-transition login-page">

        <div class="modal modal-info fade" id="modal-info" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title text-center">Recuperar Contraseña</h4>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route( 'recovery' ) }}" id="form_recovery" method="POST" name="form_recovery" role="form">

                            <h2 class="text-center title-form">Email para enviar información de recuperación de contraseña a email</h4>
                            <h5 class="alert hidden message-error text-center" role="alert"></h5>

                            <div class="row">
                                <div class="col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1 col-sm-10 col-sm-offset-1 col-xs-10 col-md-offset-1 col-xs-offset-1">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control input-lg" id="email_recovery" name="email_recovery" placeholder="Coloca tu email" />
                                        <span id="help-email_recovery" class="hidden label"></span>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2 col-sm-8 col-sm-offset-2 col-xs-8 col-md-offset-2 col-xs-offset-2">
                                            <button type="submit" class="btn btn-block btn-success" id="action_process_recovery">Recuperar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="login-box">
            <div class="login-logo">
                <a href="../../index2.html"><b>Book</b>A</a>
            </div>
            <!-- /.login-logo -->
            <div class="login-box-body">
                <p class="login-box-msg">Entrar al Sistema</p>
                
                @if ($errors->any())
                    
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route( 'login' ) }}" method="post">
                    <div class="form-group has-feedback">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{ @old( 'email' ) }}" required>
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 col-xs-offset-3">
                            <button type="submit" class="btn btn-primary btn-block btn-flat">ENTRAR</button>
                        </div>
                    </div>
                    {{ csrf_field() }}
                </form>
                <br>
                <a href="#" class="view_recovery" data-toggle="modal" data-target="#modal-info">Recuperar Contraseña</a>
                <br>
            </div>
            <!-- /.login-box-body -->
        </div>
        <!-- /.login-box -->
        <!-- jQuery 3 -->
        <script src="{{ asset( 'js/plugin/jquery.min.js' ) }}"></script>
        <!-- Bootstrap 3.3.7 -->
        <script src="{{ asset( 'js/plugin/bootstrap.min.js' ) }}"></script>
        <!-- iCheck -->
        <script src="{{ asset( 'plugins/iCheck/icheck.min.js' ) }}"></script>

        <script src="{{ asset( 'js/login.js' ) }}"></script>
        <script>
            $(function () {

                login.init();

                $('input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%' /* optional */
                });
            });
        </script>
    </body>
</html>