<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Roles;
use App\Models\Books\Borrowings;
use App\Models\Communities\CommentCommunities;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\Models\Communities\PostCommunities;
use App\Models\Communities\ImagePostCommunities;
use App\Models\Communities\LikeCommunities;
use App\Models\Communities\VideoPostCommunities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'full_name',
        'email',
        'password',
        'avatar',
        'cover',
        'gender',
        'role_id'
    ];



    public function role()
    {
        return $this->belongsTo(Roles::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notifications::class);
    }

    public function borrowings()
    {
        return $this->hasMany(Borrowings::class);
    }

    public function postCommunities()
    {
        return $this->hasMany(PostCommunities::class);
    }

    public function videoPostCommunities()
    {
        return $this->hasMany(VideoPostCommunities::class);
    }

    public function imagePostCommunities()
    {
        return $this->hasMany(ImagePostCommunities::class);
    }

    public function likeCommunities()
    {
        return $this->hasMany(LikeCommunities::class);
    }

    public function commentCommunities()
    {
        return $this->hasMany(CommentCommunities::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];



    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
