<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Container\Attributes\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Book::query()
            ->with(['author', 'categories']);

        // Search (judul / ISBN)
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('isbn', 'like', '%' . $request->search . '%');
            });
        }

        // Filter kategori
        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        // Filter penulis
        if ($request->filled('author')) {
            $query->where('author_id', $request->author);
        }

        $books = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('book.index', [
            'books' => $books,
            'categories' => Category::all(),
            'authors' => Author::all(),
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('book.create', [
            'categories' => Category::orderBy('name')->get(),
            'authors' => Author::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'required|string|max:255|unique:books,isbn',
            'author_id' => 'required|exists:authors,id',
            'categories_id' => 'required|array|min:1',
            'categories_id.*' => 'integer|exists:categories,id',
            'publisher' => 'nullable|string|max:255',
            'published_year' => 'nullable|integer',
            'pages' => 'nullable|integer',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'stock' => 'required|integer|min:0',
        ]);

        $slug = Str::slug($request->title);
        $originalslug = $slug;
        $counter = 1;
        $imagepath = null;

        while (Book::where('slug', $slug)->exists()) {
            $slug = $originalslug . '-' . $counter;
            $counter++;
        }

        if ($request->hasFile('cover_image')) {
            $imagepath = $request->file('cover_image')->store('books', 'public');
        }


        $book = Book::create([
            'title' => $request->title,
            'isbn' => $request->isbn,
            'slug' => $slug,
            'author_id' => $request->author_id,
            'publisher' => $request->publisher,
            'published_year' => $request->published_year,
            'pages' => $request->pages,
            'description' => $request->description,
            'cover_image' => $imagepath,
            'stock' => $request->stock,
            'available_stock' => $request->stock,
        ]);

        $book->categories()->attach($request->categories_id);

        return redirect()->route('books.index')->with('success', 'Buku berhasil ditambahkan');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        return view('book.edit', [
            'book' => $book,
            'categories' => Category::orderBy('name', 'asc')->get(),
            'authors' => Author::orderBy('name', 'asc')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'required|string|max:255|unique:books,isbn,' . $book->id,
            'author_id' => 'required|exists:authors,id',
            'categories_id' => 'required|array|min:1',
            'categories_id.*' => 'integer|exists:categories,id',
            'publisher' => 'nullable|string|max:255',
            'published_year' => 'nullable|integer',
            'pages' => 'nullable|integer',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'stock' => 'required|integer|min:' . $book->available_stock,
        ]);


        $slug = Str::slug($request->title);
        $originalslug = $slug;
        $counter = 1;
        $imagepath = $book->cover_image;

        while (Book::where('slug', $slug)->where('id', '!=', $book->id)->exists()) {
            $slug = $originalslug . '-' . $counter;
            $counter++;
        }

        if ($request->hasFile('cover_image')) {
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $imagepath = $request->file('cover_image')->store('books', 'public');
        }

        $oldStock = $book->stock;
        $newStock = $request->stock;

        // hitung jumlah buku yang sedang dipinjam
        $borrowed = $oldStock - $book->available_stock;

        // pastikan stok baru tidak kurang dari yang dipinjam
        if ($newStock < $borrowed) {
            return back()->withErrors([
                'stock' => 'Stok tidak boleh lebih kecil dari jumlah buku yang sedang dipinjam'
            ]);
        }

        // hitung available_stock baru
        $newAvailableStock = $newStock - $borrowed;

        $book->update([
            'title' => $request->title,
            'isbn' => $request->isbn,
            'slug' => $slug,
            'author_id' => $request->author_id,
            'publisher' => $request->publisher,
            'published_year' => $request->published_year,
            'pages' => $request->pages,
            'description' => $request->description,
            'cover_image' => $imagepath,
            'stock' => $request->stock,
            'available_stock' => $newAvailableStock,
        ]);

        $book->categories()->sync($request->categories_id);

        return redirect()->route('books.index')->with('success', 'Buku berhasil diupdate');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus');
    }
}