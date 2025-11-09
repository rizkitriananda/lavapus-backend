<?php

namespace App\Http\Controllers\Books;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Books\CategoryResource;
use App\Models\Books\Categories;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = Categories::all();
            return $this->successResponse(
                CategoryResource::collection($categories),
                'Categories retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve categories: ' . $e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation failed', 422, $validator->errors());
            }

            $category = Categories::create($validator->validated());
            return $this->successResponse(
                new CategoryResource($category),
                'Category created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create category: ' . $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            $category = Categories::findOrFail($id);
            return $this->successResponse(
                new CategoryResource($category),
                'Category retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Category not found', 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $category = Categories::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation failed', 422, $validator->errors());
            }

            $category->update($validator->validated());
            return $this->successResponse(
                new CategoryResource($category),
                'Category updated successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update category: ' . $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $category = Categories::findOrFail($id);
            $category->delete();
            return $this->successResponse(null, 'Category deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete category: ' . $e->getMessage(), 500);
        }
    }
}
