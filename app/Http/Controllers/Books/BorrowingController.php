<?php

namespace App\Http\Controllers\Books;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use App\Models\Books\Borrowings;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Books\BorrowingResource;

class BorrowingController extends Controller
{
    public function index()
    {
        try {
            $borrowings = Borrowings::with(['user', 'book'])->get();
            return $this->successResponse(
                BorrowingResource::collection($borrowings),
                'Borrowings retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve borrowings: ' . $e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validation = $request->validate([
                'book_id' => [Rule::exists('books','id'), 'required'],
                'loan_date' => 'int|required'
            ]);
        
            $validation['loan_date'] = (int) $validation['loan_date'];
            $borrowCode = 'BC-' . strtoupper(Str::ulid());
        
            $data = Borrowings::create([
                'user_id' => Auth::id(),
                'book_id' => $request->book_id,        
                'borrow_code' => $borrowCode,
                'loan_date' => $request->loan_date,
                'return_date' => Carbon::now()->addDays($validation['loan_date']),
                'status' => 'loaned'
            ]); 
        
            return response()->json([
                'status' => 'success',
                'message' => 'Book borrowing data successfully added',
                'data' => new BorrowingResource($data)
            ], 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create borrowing: ' . $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            $borrowing = Borrowings::with(['user', 'book'])->findOrFail($id);
            return $this->successResponse(
                new BorrowingResource($borrowing),
                'Borrowing retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Borrowing not found', 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $borrowing = Borrowings::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'user_id' => 'sometimes|exists:users,id',
                'book_id' => 'sometimes|exists:books,id',
                'borrow_code' => 'sometimes|string|max:255|unique:borrowings,borrow_code,' . $id,
                'loan_date' => 'sometimes|date',
                'return_date' => 'nullable|date',
                'status' => 'sometimes|in:loaned,returned',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation failed', 422, $validator->errors());
            }

            $borrowing->update($validator->validated());
            $borrowing->load(['user', 'book']);

            return $this->successResponse(
                new BorrowingResource($borrowing),
                'Borrowing updated successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update borrowing: ' . $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $borrowing = Borrowings::findOrFail($id);
            $borrowing->delete();
            return $this->successResponse(null, 'Borrowing deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete borrowing: ' . $e->getMessage(), 500);
        }
    }
}
