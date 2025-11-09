<?php

namespace App\Http\Resources\Communities;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Communities\LikeCommunityResource;
use App\Http\Resources\Communities\CommentCommunityResource;
use App\Http\Resources\Communities\ImagePostCommunityResource;
use App\Http\Resources\Communities\VideoPostCommunityResource;

class PostCommunityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'user' => new UserResource($this->whenLoaded('user')),
            'videos' => VideoPostCommunityResource::collection($this->whenLoaded('videos')),
            'images' => ImagePostCommunityResource::collection($this->whenLoaded('images')),
            'likes' => LikeCommunityResource::collection($this->whenLoaded('likes')),
            'likes_count' => $this->when(isset($this->likes_count), $this->likes_count),
            'comments' => CommentCommunityResource::collection($this->whenLoaded('comments')),
            'comments_count' => $this->when(isset($this->comments_count), $this->comments_count),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
