<?php

namespace App\Http\Controllers\Communities;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Communities\VideoPostCommunities;
use App\Http\Resources\Communities\VideoPostCommunityResource;


class VideoPostCommunityController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'post_community_id' => 'required|exists:post_communities,id',
                'user_id' => 'required|exists:users,id',
                'video' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation failed', 422, $validator->errors());
            }

            $video = VideoPostCommunities::create($validator->validated());
            $video->load('user');

            return $this->successResponse(
                new VideoPostCommunityResource($video),
                'Video added successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to add video: ' . $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $video = VideoPostCommunities::findOrFail($id);
            $video->delete();
            return $this->successResponse(null, 'Video deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete video: ' . $e->getMessage(), 500);
        }
    }
}