<?php

namespace App\Http\Controllers\Communities;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Communities\PostCommunities;
use App\Http\Resources\Communities\PostCommunityResource;

class PostCommunityController extends Controller
{
    public function index()
    {
        try {
            $posts = PostCommunities::with(['user', 'videos', 'images'])
                ->withCount(['likes', 'comments'])
                ->latest()
                ->get();
            
            return $this->successResponse(
                PostCommunityResource::collection($posts),
                'Posts retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve posts: ' . $e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'content' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation failed', 422, $validator->errors());
            }

            $post = PostCommunities::create($validator->validated());
            $post->load(['user', 'videos', 'images']);

            return $this->successResponse(
                new PostCommunityResource($post),
                'Post created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create post: ' . $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            $post = PostCommunities::with(['user', 'videos', 'images', 'likes', 'comments'])
                ->withCount(['likes', 'comments'])
                ->findOrFail($id);
            
            return $this->successResponse(
                new PostCommunityResource($post),
                'Post retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Post not found', 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $post = PostCommunities::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'content' => 'sometimes|string',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation failed', 422, $validator->errors());
            }

            $post->update($validator->validated());
            $post->load(['user', 'videos', 'images']);

            return $this->successResponse(
                new PostCommunityResource($post),
                'Post updated successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update post: ' . $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $post = PostCommunities::findOrFail($id);
            $post->delete();
            return $this->successResponse(null, 'Post deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete post: ' . $e->getMessage(), 500);
        }
    }
}
