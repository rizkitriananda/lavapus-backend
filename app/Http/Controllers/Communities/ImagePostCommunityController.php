<?php

namespace App\Http\Controllers\Communities;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Communities\ImagePostCommunityResource;
use App\Models\Communities\ImagePostCommunities;
use Illuminate\Support\Facades\Validator;

class ImagePostCommunityController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'post_community_id' => 'required|exists:post_communities,id',
                'user_id' => 'required|exists:users,id',
                'image' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Validation failed', 422, $validator->errors());
            }

            $image = ImagePostCommunities::create($validator->validated());
            $image->load('user');

            return $this->successResponse(
                new ImagePostCommunityResource($image),
                'Image added successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to add image: ' . $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $image = ImagePostCommunities::findOrFail($id);
            $image->delete();
            return $this->successResponse(null, 'Image deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete image: ' . $e->getMessage(), 500);
        }
    }
}
