<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
class BookController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-book|edit-book|delete-book', ['only' => ['index','show']]);
        $this->middleware('permission:create-book', ['only' => ['create','store']]);
        $this->middleware('permission:edit-book', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-book', ['only' => ['destroy','trashedBooks','restoreBook','deleteBook']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $books = Book::latest('id')->paginate(3);
        $total_trashed = Book::onlyTrashed()->get()->count();
        return view('admin.books.index',['books'=>$books, 'total_trashed'=>$total_trashed]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       return view('admin.books.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:255|unique:books,isbn', // Ensure uniqueness among books
            'description' => 'nullable|string',
            'book_type' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'instock' => 'nullable|integer|min:0',
            'cost' => 'nullable|numeric|min:0',
        ]);
         $input = $request->all();
         Book::create($input);
         return redirect()->route('book.index')
         ->withSuccess('New book is added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return view('admin.books.show', ['book'=>$book]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        return view('admin.books.edit', [
            'book' => $book]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
    
        // Validate the incoming request data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' =>  'nullable|string|max:255|unique:books,isbn,'.$book->id,
            'description' => 'nullable|string',
            'book_type' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'instock' => 'nullable|integer|min:0',
            'cost' => 'nullable|numeric|min:0',
        ]);

        // Update the book with the validated data
        $book->update($validatedData);

        // Redirect back to the books index page
        return redirect()->route('books.index')
            ->withSuccess('Book updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('books.index')
                ->withSuccess('Book is moved to trash successfully.');
    }

   public function trashedBooks() {
        $books = Book::onlyTrashed()->paginate(3);      
        return view('admin.books.trashed', compact('books'));
    }

    public function restoreBook(Request $request, $id) {

        Book::withTrashed()->find($id)->restore();
        return redirect()->route('books.trashed')->with('success', 'Book restored successfully.');
     }
  
     public function deleteBook(Request $request, $id) {

        $book = Book::withTrashed()->find($id);
        $book->forceDelete();
        return redirect()->route('books.trashed')->with('success', 'User deleted successfully.');
     }


}
