<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Booka | Recuperar Contraseña</title>

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
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>
    <body class="hold-transition lockscreen">
        <!-- Automatic element centering -->
        <div class="lockscreen-wrapper">
            <div class="lockscreen-logo">
                <a href="{{ route( 'login' ) }}"><b>Book</b>A</a>
            </div>
            <!-- User name -->
            <div class="lockscreen-name">{{ $user[0]->user_name . ' ' . $user[0]->user_lastName }}</div>
            <!-- START LOCK SCREEN ITEM -->
            <div class="lockscreen-item">
                <!-- lockscreen image -->
                <div class="lockscreen-image">
                    <img src="{{ asset( 'img/user1-128x128.jpg' ) }}" alt="{{ $user[0]->user_name . ' ' . $user[0]->user_lastName }}">
                </div>
                
                @if( $visibility_change )
                    <form action="{{ route( 'forgot' ) }}" class="lockscreen-credentials" id="form_change_password" method="POST" name="form_change_password" role="form">

                        <h5 class="alert hidden message-error text-center" role="alert"></h5>

                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña">
                            <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="Confirmar Contraseña">
                            <input id="key_pw" name="key_pw" type="hidden" value="{{ $url }}">
                            <div class="input-group-btn">
                                <button type="submit" class="btn"><i class="fa fa-arrow-right text-muted"></i></button>
                            </div>
                        </div>
                    </form>
                @else

                    <h5 class="alert alert-danger text-center" role="alert">Lo sentimos ya caduco ese cambio de contraseña</h5>

                    <div class="row">
                        <div class="col-md-4 col-md-offset-2 col-lg-4 col-lg-offset-2 col-xs-12 col-sm-4 col-sm-offset-2 col-xs-offset-0">
                            <p class="text-center">
                                <a class="btn btn-block btn-theme btn-orange" href="{{ route( 'login' ) }}">Loguearse</a>
                            </p>
                        </div>
                    </div>
                @endif
            </div>
            <!-- /.lockscreen-item -->
            <div class="help-block text-center">
                Ingrese su nueva contraseña
            </div>
            <div class="lockscreen-footer text-center">
                Copyright &copy; <b><a href="https://juanrofranco.com" target="_blank" class="text-black">juanrofranco.com</a></b><br>
                All rights reserved
            </div>
        </div>
        <script src="{{ asset( 'js/plugin/jquery.min.js' ) }}"></script>
        <script src="{{ asset( 'js/plugin/bootstrap.min.js' ) }}"></script>
        <script src="{{ asset( 'js/login.js' ) }}"></script>
        <script>
            login.change_password();
        </script>
    </body>
</html>