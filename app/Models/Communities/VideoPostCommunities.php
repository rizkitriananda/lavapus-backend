<?php

namespace App\Models\Communities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VideoPostCommunities extends Model
{
    use HasFactory;

    protected $fillable = [
        'video',
        'post_community_id',
        'user_id',
    ];

    public function postCommunity()
    {
        return $this->belongsTo(PostCommunities::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
