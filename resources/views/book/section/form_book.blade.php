@if( !empty( $book->book_id ) )
    
    <form action="{{ route( 'book-update' ) }}" method="POST" role="form" enctype="multipart/form-data">
@else
    
    <form action="{{ route( 'book-save' ) }}" method="POST" role="form" enctype="multipart/form-data">
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
                    <input type="text" class="form-control" id="name" name="name" placeholder="Nombre(s)" value="{{ ( !empty( $book->book_name )? $book->book_name : old( 'name' ) ) }}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="description">Descripción</label>
                    <textarea class="form-control" id="description" name="description" placeholder="Descripción" required>{{ ( !empty( $book->book_description )? $book->book_description : old( 'description' ) ) }}</textarea>
                </div>
            </div>
        </div>
        {{ csrf_field() }}
        <div class="row">

            <div class="col-md-5">
                <div class="form-group">
                    <label for="media">Imagen</label>
                    <input type="file" class="form-control" id="media" name="media" placeholder="Imagen" required>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="autor">Autor</label>
                    <input type="text" class="form-control" id="autor" name="autor" placeholder="Autor" value="{{ ( !empty( $book->book_autor )? $book->book_autor : old( 'autor' ) ) }}" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="created">Fecha Publicación</label>
                    <input type="number" class="form-control" id="created" name="created" placeholder="Fecha Publicación" value="{{ ( !empty( $book->book_created )? $book->book_created : old( 'created' ) ) }}" maxlength="4" required>
                </div>
            </div>
        </div>

        @if( !empty( $book->book_id ) )

            <input type="hidden" id="id" name="id" value="{{ $book->book_encrypted }}">
        @endif
    </div>

    @if( session( "us3r-rol" ) == 1 )
        <div class="box-footer">
            <div class="row">

                <div class="col-md-6 col-md-offset-3">
                    <button type="submit" class="btn btn-success btn-block">{{ ( !empty( $book->book_email )? 'Modificar Libro' : 'Crear Libro' ) }}</button>
                </div>
            </div>
        </div>
    @endif
</form>