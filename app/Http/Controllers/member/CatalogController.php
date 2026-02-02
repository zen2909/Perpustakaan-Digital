<?php

namespace App\Http\Controllers\member;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index()
    {
        $books = Book::orderBy('id', 'asc')->get();

        return view('member.book-catalog', compact('books'));
    }
}