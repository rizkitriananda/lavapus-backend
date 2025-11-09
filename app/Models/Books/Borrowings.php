<?php

namespace App\Models\Books;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Borrowings extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'borrow_code',
        'loan_date',
        'return_date',
        'status',
    ];

    protected $casts = [
        'loan_date' => 'date',
        'return_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Books::class);
    }
}
