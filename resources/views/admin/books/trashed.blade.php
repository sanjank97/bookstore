@extends('admin.layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
         <div class="float-start">
            Trashed Books
         </div>
         <div class="float-end">
             <a href="{{ route('books.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
        </div>
    </div>
 
    <div class="card-body">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                <th scope="col">S#</th>
                <th scope="col">Title</th>
                <th scope="col">Author</th>
                <th scope="col">ISBN</th>
                <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($books as $book)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $book->title }}</td>
                    <td>{{ $book->author }}</td>
                    <td>{{ $book->isbn }}</td>
                    <td>
                        <form action="{{ route('books.restore', $book->id) }}" method="post">
                            @csrf
                            @method('POST')
                            <button type="submit" class="btn btn-primary btn-sm">Restore</button>
                        </form>
                        <form action="{{ route('books.delete', $book->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                    <td colspan="5">
                        <span class="text-danger">
                            <strong>No Book Found!</strong>
                        </span>
                    </td>
                @endforelse
            </tbody>
        </table>

        {{ $books->links() }}

    </div>
</div>
    
@endsection
