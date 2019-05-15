@extends( "main" )

@section( "site_title", $site_title )

@section( "content" )
	<div class="row">
        <div class="col-md-3">
            
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <img class="profile-user-img img-responsive img-circle" src="{{ asset( 'img/' . $book->book_media ) }}" alt="{{ $book->user_name }}">
                    <h3 class="profile-username text-center">{{ $book->user_name . ' ' . $book->user_lastName }}</h3>
                    <p class="text-muted text-center">{{ $book->rol_name }}</p>
                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                            <b># Veces Prestado</b> <a class="pull-right">{{ count( $book_lend ) }}</a>
                        </li>
                    </ul>

                    @if( session( "us3r-rol" ) == 1 )
				        
				        <a href="{{ route( 'book-delete' ) . '/?id=' . $book->user_encrypted }}" class="btn btn-danger btn-block"><b>Eliminar Libro</b></a>
				    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#activity" data-toggle="tab">Prestamos</a></li>
                    <li><a href="#settings" data-toggle="tab">Datos</a></li>
                </ul>
                <div class="tab-content">
                    <div class="active tab-pane" id="activity">

                    	@if( count( $book_lend ) > 0 )

	                        @for( $b = 0; $b < count( $book_lend ); $b++ )

		                        <div class="post">
		                            <div class="user-block">
		                                <img class="img-circle img-bordered-sm" src="{{ asset( 'img/' . $book_lend[$b]->book_media ) }}" alt="{{ $book_lend[$b]->book_name }}">
		                                <span class="username">
		                                <a {{--href="{{ route( 'book', $book_lend[$b]->book_url ) }}"--}}href="#">{{ $book_lend[$b]->book_name }}</a>
		                                </span>
		                                <span class="description">{{ $book_lend[$b]->book_autor . ' - ' . $book_lend[$b]->book_created }}</span>
		                            </div>
		                            <!-- /.user-block -->
		                            <p>{{ $book_lend[$b]->book_description }}</p>
		                        </div>
		                	@endfor
		                @else

		                	<h2 class="text-center">No se ha prestado aún</h2>
		                @endif
                    </div>

                    <div class="tab-pane" id="settings">

                    	@include( "book.section.form_book" )
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section( "script_extend" )
@endsection