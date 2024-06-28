<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Récupère tous les livres et renvoie une réponse JSON avec un statut 200
        $books = Book::all();
        return response()->json($books, 200);
    }


    /**
     * Store a newly created resource in storage.
     */

    public function store(StoreBookRequest $request)
    {
        DB::beginTransaction();

        try {
            Log::info('Starting book creation');

            $book = new Book();
            $book->title = $request->title;
            $book->author = $request->author;
            $book->description = $request->description;
            $book->status = $request->status;
            $book->publication_year = $request->publication_year;
            $book->genre = $request->genre;
            $book->price = $request->price;
            $book->quantity = $request->quantity;

            if ($request->hasFile('images')) {
                Log::info('Processing image upload');

                if ($book->images) {
                    Storage::disk('public')->delete(str_replace('/storage', '', $book->images));
                }

                $path = $request->file('images')->store('photos', 'public');
                $book->images = Storage::url($path);
            }

            $book->save();
            Log::info('Book saved successfully');

            DB::commit();

            return response()->json($book, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error occurred while creating the book: ' . $e->getMessage());

            return response()->json(['error' => 'An error occurred while creating the book.'], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        // Récupère le livre spécifié par son ID et renvoie une réponse JSON avec le livre et un statut 200
        $book = Book::findOrFail($book->id);
        return response()->json($book, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, $id)
    {
        $book = Book::query()->findOrFail($id);

//        Log::info('Données reçues:', $book->all());

        if ($request->hasFile('images')) {
            // Supprimer l'ancienne photo si elle existe
            if ($book->images) {
                Storage::disk('public')->delete(str_replace('/storage', '', $book->images));
            }

            $path = $request->file('images')->store('profile_photos', 'public');
            $book->images = Storage::url($path);
        }

        $book->update($request->except('images'));

        return response()->json($book, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = Book::query()->findOrFail($id);

        if ($user->images) {
            Storage::disk('public')->delete(str_replace('/storage', '', $user->images));
        }

        $user->delete();

        return response()->json(['message' => 'Utilisateur supprimé avec succès']);
    }
}
