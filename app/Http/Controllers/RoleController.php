<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use Illuminate\Http\Request;
use App\Http\Resources\RoleResource;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
   public function index()
    {
        try {
            $roles = Roles::all();
            return $this->successResponse(
                RoleResource::collection($roles),
                'Roles retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve roles: ' . $e->getMessage(), 500);
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

            $role = Roles::create($validator->validated());
            return $this->successResponse(
                new RoleResource($role),
                'Role created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create role: ' . $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            $role = Roles::findOrFail($id);
            return $this->successResponse(
                new RoleResource($role),
                'Role retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Role not found', 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $role = Roles::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation failed', 422, $validator->errors());
            }

            $role->update($validator->validated());
            return $this->successResponse(
                new RoleResource($role),
                'Role updated successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update role: ' . $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $role = Roles::findOrFail($id);
            $role->delete();
            return $this->successResponse(null, 'Role deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete role: ' . $e->getMessage(), 500);
        }
    }
}
