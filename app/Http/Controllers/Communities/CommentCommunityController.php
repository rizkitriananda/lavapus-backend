<?php

namespace App\Http\Controllers\Communities;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Communities\CommentCommunities;
use App\Http\Resources\Communities\CommentCommunityResource;

class CommentCommunityController extends Controller
{
    public function index($postId)
    {
        try {
            $comments = CommentCommunities::where('post_community_id', $postId)
                ->with('user')
                ->latest()
                ->get();
            
            return $this->successResponse(
                CommentCommunityResource::collection($comments),
                'Comments retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve comments: ' . $e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'post_community_id' => 'required|exists:post_communities,id',
                'user_id' => 'required|exists:users,id',
                'comment' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation failed', 422, $validator->errors());
            }

            $comment = CommentCommunities::create($validator->validated());
            $comment->load('user');

            return $this->successResponse(
                new CommentCommunityResource($comment),
                'Comment created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create comment: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $comment = CommentCommunities::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'comment' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation failed', 422, $validator->errors());
            }

            $comment->update($validator->validated());
            $comment->load('user');

            return $this->successResponse(
                new CommentCommunityResource($comment),
                'Comment updated successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update comment: ' . $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $comment = CommentCommunities::findOrFail($id);
            $comment->delete();
            return $this->successResponse(null, 'Comment deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete comment: ' . $e->getMessage(), 500);
        }
    }
}
