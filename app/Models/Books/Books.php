<?php

namespace App\Models\Books;

use App\Models\Books\Borrowings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Books extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'number_book',
        'publisher',
        'cover',
        'publication_year',
        'category_id',
        'stock',
    ];

    public function category()
    {
        return $this->belongsTo(Categories::class);
    }

    public function borrowings()
    {
        return $this->hasMany(Borrowings::class);
    }
}
