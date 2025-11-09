<?php

namespace App\Models\Comunities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BranchLibraries extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'maps_link',
    ];
}
