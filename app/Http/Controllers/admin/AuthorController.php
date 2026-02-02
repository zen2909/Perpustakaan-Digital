<?php

namespace App\Http\Controllers\admin;

use App\Models\Author;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authors = Author::orderBy('id', 'asc')->get();
        return view('author.index', compact('authors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('author.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'biography' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'nationality' => 'nullable|string|max:255',
            'birth_year' => 'nullable|integer',
        ]);

        $photopath = null;

        if ($request->hasFile('photo')) {
            $photopath = $request->file('photo')->store('authors', 'public');
        }

        Author::create([
            'name' => $request->name,
            'biography' => $request->biography,
            'photo' => $photopath,
            'nationality' => $request->nationality,
            'birth_year' => $request->birth_year,

        ]);

        return redirect()->route('authors.index')->with('success', 'Author berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**$author
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $author = Author::findOrFail($id);
        return view('author.edit', compact('author'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'biography' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'nationality' => 'nullable|string|max:255',
            'birth_year' => 'nullable|integer',
        ]);

        $author = Author::findOrFail($id);

        $data = [
            'name' => $request->name,
            'biography' => $request->biography,
            'nationality' => $request->nationality,
            'birth_year' => $request->birth_year,
        ];

        if ($request->hasFile('photo')) {
            if ($author->photo) {
                Storage::disk('public')->delete($author->photo);
            }
            $data['photo'] = $request->file('photo')->store('authors', 'public');
        }

        $author->update($data);

        return redirect()->route('authors.index')->with('success', 'Author berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $author = Author::findOrFail($id);

        if ($author->photo) {
            Storage::disk('public')->delete($author->photo);
        }

        $author->delete();


        return redirect()->route('authors.index')->with('success', 'Author berhasil Dihapus');
    }
}