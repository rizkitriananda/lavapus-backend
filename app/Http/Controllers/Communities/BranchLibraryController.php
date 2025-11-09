<?php

namespace App\Http\Controllers\Communities;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Comunities\BranchLibraries;
use App\Http\Resources\Communities\BranchLibaryResource;

class BranchLibraryController extends Controller
{
    public function index()
    {
        try {
            $branches = BranchLibraries::all();
            return $this->successResponse(
                BranchLibaryResource::collection($branches),
                'Branch libraries retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve branch libraries: ' . $e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'address' => 'required|string',
                'maps_link' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation failed', 422, $validator->errors());
            }

            $branch = BranchLibraries::create($validator->validated());
            return $this->successResponse(
                new BranchLibaryResource($branch),
                'Branch library created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create branch library: ' . $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            $branch = BranchLibraries::findOrFail($id);
            return $this->successResponse(
                new BranchLibaryResource($branch),
                'Branch library retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Branch library not found', 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $branch = BranchLibraries::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|string|max:255',
                'address' => 'sometimes|string',
                'maps_link' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation failed', 422, $validator->errors());
            }

            $branch->update($validator->validated());
            return $this->successResponse(
                new BranchLibaryResource($branch),
                'Branch library updated successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update branch library: ' . $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $branch = BranchLibraries::findOrFail($id);
            $branch->delete();
            return $this->successResponse(null, 'Branch library deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete branch library: ' . $e->getMessage(), 500);
        }
    }
}
