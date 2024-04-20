@extends('admin.layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
         <div class="float-start">
            Manage Books 
         </div>
         <div class="float-end">
             <a href="{{ route('books.trashed')}}" class="btn btn-primary btn-sm"><i class="bi bi-trash"></i> Trashed Books({{$total_trashed}})</a>
        </div>
    </div>
    <div class="card-body">
        @can('create-book')
        <a href="{{ route('books.create') }}" class="btn btn-success btn-sm my-2"><i class="bi bi-plus-circle"></i> Add New Book</a>
        @endcan
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th scope="col">S#</th>
                    <th scope="col">Title</th>
                    <th scope="col">Author</th>
                    <th scope="col">ISBN</th>
                    <th scope="col" style="width: 250px;">Action</th>
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
                        <form action="{{ route('books.destroy', $book->id) }}" method="post">
                            @csrf
                            @method('DELETE')

                            <a href="{{ route('books.show', $book->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-eye"></i> Show</a>
                            @can('edit-book')
                                <a href="{{ route('books.edit', $book->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i> Edit</a>
                            @endcan
                            @can('delete-book')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Do you want to delete this book?');"><i class="bi bi-trash"></i> Delete</button>
                            @endcan
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <span class="text-danger">
                            <strong>No Books Found!</strong>
                        </span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{ $books->links() }}
    </div>
</div>
@endsection
