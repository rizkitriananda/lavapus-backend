<?php

namespace App\Http\Controllers\Books;

use App\Models\Books\Books;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Books\BookResource;

class BookController extends Controller
{
    public function index()
    {
        try {
            $books = Books::with('category')->get();
            return $this->successResponse(
                BookResource::collection($books),
                'Books retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve books: ' . $e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'author' => 'required|string|max:255',
                'number_book' => 'required|string|max:255',
                'publisher' => 'nullable|string|max:255',
                'cover' => 'nullable|image|mimes:jpg,png,svg,webp',
                'publication_year' => 'nullable|integer',
                'category_id' => 'required|exists:categories,id',
                'stock' => 'required|integer|min:0',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation failed', 422, $validator->errors());
            }

            $book = Books::create($validator->validated());
            $book->load('category');

            return $this->successResponse(
                new BookResource($book),
                'Book created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create book: ' . $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            $book = Books::with('category')->findOrFail($id);
            return $this->successResponse(
                new BookResource($book),
                'Book retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Book not found', 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $book = Books::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'title' => 'sometimes|string|max:255',
                'author' => 'sometimes|string|max:255',
                'number_book' => 'sometimes|string|max:255',
                'publisher' => 'sometimes|string|max:255',
                'cover' => 'sometimes|image|mimes:jpg,png,svg',
                'publication_year' => 'sometimes|integer',
                'category_id' => 'sometimes|exists:categories,id',
                'stock' => 'sometimes|integer|min:0',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation failed', 422, $validator->errors());
            }

            $book->update($validator->validated());
            $book->load('category');

            return $this->successResponse(
                new BookResource($book),
                'Book updated successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update book: ' . $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $book = Books::findOrFail($id);
            $book->delete();
            return $this->successResponse(null, 'Book deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete book: ' . $e->getMessage(), 500);
        }
    }
}
