<?php

namespace App\Http\Controllers\Communities;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Communities\LikeCommunityResource;
use Illuminate\Support\Facades\Validator;
use App\Models\Communities\LikeCommunities;

class LikeCommunityController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'post_community_id' => 'required|exists:post_communities,id',
                'user_id' => 'required|exists:users,id',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation failed', 422, $validator->errors());
            }

            // Check if already liked
            $existingLike = LikeCommunities::where('post_community_id', $request->post_community_id)
                ->where('user_id', $request->user_id)
                ->first();

            if ($existingLike) {
                return $this->errorResponse('Post already liked', 400);
            }

            $like = LikeCommunities::create($validator->validated());
            $like->load('user');

            return $this->successResponse(
                new LikeCommunityResource($like),
                'Post liked successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to like post: ' . $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $like = LikeCommunities::findOrFail($id);
            $like->delete();
            return $this->successResponse(null, 'Like removed successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to remove like: ' . $e->getMessage(), 500);
        }
    }

    // Unlike by post_id and user_id
    public function unlike(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'post_community_id' => 'required|exists:post_communities,id',
                'user_id' => 'required|exists:users,id',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation failed', 422, $validator->errors());
            }

            $like = LikeCommunities::where('post_community_id', $request->post_community_id)
                ->where('user_id', $request->user_id)
                ->first();

            if (!$like) {
                return $this->errorResponse('Like not found', 404);
            }

            $like->delete();
            return $this->successResponse(null, 'Like removed successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to remove like: ' . $e->getMessage(), 500);
        }
    }
}
