@extends( "main" )

@section( "site_title", $site_title )

@section( "content" )

    <div class="row">
        
        <div class="col-xs-4">
            
            <a href="{{ route( 'home' ) }}"><button class="btn btn-block btn-primary" type="button">Lista de Usuario</button></a>
        </div>
    </div>
    
    <br>
    
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">

                @include( "user.section.form_user" )
            </div>
        </div>
    </div>
@endsection

@section( "script_extend" )
    
    
@endsection