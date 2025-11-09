<?php

namespace App\Models\Communities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostCommunities extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function videos()
    {
        return $this->hasMany(VideoPostCommunities::class);
    }

    public function images()
    {
        return $this->hasMany(ImagePostCommunities::class);
    }

    public function likes()
    {
        return $this->hasMany(LikeCommunities::class);
    }

    public function comments()
    {
        return $this->hasMany(CommentCommunities::class);
    }
}
