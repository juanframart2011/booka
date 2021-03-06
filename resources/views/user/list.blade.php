@extends( "main" )

@section( "site_title", $site_title )

@section( "content" )
    
    @if( session( "us3r-rol" ) == 1 )
        <div class="row">
            
            <div class="col-xs-4">
                
                <a href="{{ route( 'user-register' ) }}"><button class="btn btn-block btn-primary" type="button">Registrar Usuario</button></a>
            </div>
        </div>
    @endif
    
    <br>
    <div class="row">
        <div class="col-xs-12">
            <!-- /.box -->
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Lista de Usuarios</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Rol</th>
                                @if( session( "us3r-rol" ) == 1 )
                                    
                                    <th>Fecha de Creación</th>
                                @endif
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for( $u = 0; $u < count( $users ); $u++ )

                                <tr>
                                    <td>{{ $users[$u]->user_name . ' ' . $users[$u]->user_lastName }}</td>
                                    <td>{{ $users[$u]->user_email }}</td>
                                    <td>{{ $users[$u]->rol_name }}</td>

                                    @if( session( "us3r-rol" ) == 1 )
                                        
                                        <td>{{ $users[$u]->user_creationDate }}</td>
                                    @endif
                                    <td>
                                        <a href="{{ route( 'user' ) . '/?id=' . $users[$u]->user_encrypted }}"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                        @if( session( "us3r-rol" ) == 1 )

                                            <a href="{{ route( 'user-delete' ) . '/?id=' . $users[$u]->user_encrypted }}"><i class="fa fa-trash" style="color:red" aria-hidden="true"></i></a>
                                        @endif
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Rol</th>
                                @if( session( "us3r-rol" ) == 1 )
                                
                                    <th>Engine version</th>
                                @endif
                                <th>Acciones</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section( "script_extend" )
    
    <script>
        $(function () {
            $('#example1').DataTable();
            $('#example2').DataTable({
                'paging'      : true,
                'lengthChange': false,
                'searching'   : false,
                'ordering'    : true,
                'info'        : true,
                'autoWidth'   : false
            });
        });
    </script>
@endsection