@extends('admin.layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Book Information
                </div>
                <div class="float-end">
                    <a href="{{ route('books.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>
            <div class="card-body">

                <div class="mb-3 row">
                    <label for="title" class="col-md-4 col-form-label text-md-end text-start"><strong>Title:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $book->title }}
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="author" class="col-md-4 col-form-label text-md-end text-start"><strong>Author:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $book->author }}
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="isbn" class="col-md-4 col-form-label text-md-end text-start"><strong>ISBN:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $book->isbn }}
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="description" class="col-md-4 col-form-label text-md-end text-start"><strong>Description:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $book->description ?: 'N/A' }}
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="book_type" class="col-md-4 col-form-label text-md-end text-start"><strong>Book Type:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $book->book_type ?: 'N/A' }}
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="language" class="col-md-4 col-form-label text-md-end text-start"><strong>Language:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $book->language ?: 'N/A' }}
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="publisher" class="col-md-4 col-form-label text-md-end text-start"><strong>Publisher:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $book->publisher ?: 'N/A' }}
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="location" class="col-md-4 col-form-label text-md-end text-start"><strong>Location:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $book->location ?: 'N/A' }}
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="instock" class="col-md-4 col-form-label text-md-end text-start"><strong>In Stock:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $book->instock ?: 'N/A' }}
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="cost" class="col-md-4 col-form-label text-md-end text-start"><strong>Cost:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $book->cost ?: 'N/A' }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
