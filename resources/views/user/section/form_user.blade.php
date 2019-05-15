@if( !empty( $user->user_id ) )
    
    <form action="{{ route( 'user-update' ) }}" method="POST" role="form">
@else
    
    <form action="{{ route( 'user-save' ) }}" method="POST" role="form">
@endif

    <div class="box-body">

        @if ($errors->any())
    
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Nombre(s)</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Nombre(s)" value="{{ ( !empty( $user->user_name )? $user->user_name : old( 'name' ) ) }}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="lastName">Apellidos</label>
                    <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Apellidos" value="{{ ( !empty( $user->user_lastName )? $user->user_lastName : old( 'lastName' ) ) }}" required>
                </div>
            </div>
        </div>
        {{ csrf_field() }}
        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label for="email">Correo Electronico</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Correo Electronico" value="{{ ( !empty( $user->user_email )? $user->user_email : old( 'email' ) ) }}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="rol">Select</label>
                    <select class="form-control" id="rol" name="rol" required>
                        <option>Selecciona Rol</option>
                        @for( $r = 0; $r < count( $rols ); $r++ )

                            @if( old( 'rol' ) == $rols[$r]->rol_id || ( !empty( $user->rol_id ) && $user->rol_id == $rols[$r]->rol_id ) )

                                <option value="{{ $rols[$r]->rol_encrypted }}" selected>{{ $rols[$r]->rol_name }}</option>
                            @else
                                <option value="{{ $rols[$r]->rol_encrypted }}">{{ $rols[$r]->rol_name }}</option>
                            @endif
                        @endfor
                    </select>
                </div>
            </div>
        </div>

        @if( !empty( $user->user_id ) )

            <input type="hidden" id="id" name="id" value="{{ $user->user_encrypted }}">
        @endif

        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="password_confirmed">Repetir Contraseña</label>
                    <input type="password" class="form-control" id="password_confirmed" name="password_confirmed" placeholder="Repetir Contraseña" required>
                </div>
            </div>
        </div>
    </div>

    @if( session( "us3r-rol" ) == 1 || ( !empty( $user->user_id ) && $user->user_id == session( "us3r-id" ) ) )
        <div class="box-footer">
            <div class="row">

                <div class="col-md-6 col-md-offset-3">
                    <button type="submit" class="btn btn-success btn-block">{{ ( !empty( $user->user_email )? 'Modificar usuario' : 'Crear Usuario' ) }}</button>
                </div>
            </div>
        </div>
    @endif
</form>