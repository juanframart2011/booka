@extends( "main" )

@section( "site_title", $site_title )

@section( "content" )

    <div class="row">
        
        <div class="col-xs-4">
            
            <a href="{{ route( 'book-home' ) }}"><button class="btn btn-block btn-primary" type="button">Lista de Libro</button></a>
        </div>
    </div>
    
    <br>
    
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">

                @include( "book.section.form_book" )
            </div>
        </div>
    </div>
@endsection

@section( "script_extend" )
    
    
@endsection