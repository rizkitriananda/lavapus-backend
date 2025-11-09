<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = User::with('role')->get();
            return $this->successResponse(
                UserResource::collection($users),
                'Users retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve users: ' . $e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'full_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'avatar' => 'nullable|string',
                'cover' => 'nullable|string',
                'gender' => 'nullable|in:male,female',
                'role_id' => 'required|exists:roles,id',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation failed', 422, $validator->errors());
            }

            $data = $validator->validated();
            $data['password'] = Hash::make($data['password']);

            $user = User::create($data);
            $user->load('role');

            return $this->successResponse(
                new UserResource($user),
                'User created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create user: ' . $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            $user = User::with('role')->findOrFail($id);
            return $this->successResponse(
                new UserResource($user),
                'User retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('User not found', 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'full_name' => 'sometimes|string|max:255',
                'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
                'password' => 'sometimes|string|min:8',
                'avatar' => 'nullable|string',
                'cover' => 'nullable|string',
                'gender' => 'nullable|in:male,female',
                'role_id' => 'sometimes|exists:roles,id',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation failed', 422, $validator->errors());
            }

            $data = $validator->validated();
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            $user->update($data);
            $user->load('role');

            return $this->successResponse(
                new UserResource($user),
                'User updated successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update user: ' . $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return $this->successResponse(null, 'User deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete user: ' . $e->getMessage(), 500);
        }
    }
}
