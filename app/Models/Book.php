<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'writer',
        'publisher',
        'pubyear',
        'category_id',
        'stock',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'borrows', 'book_id', 'user_id');
    }

    public function borrows()
    {
        return $this->hasMany(Borrow::class, 'book_id', 'id');
    }

    public function review()
    {
        return $this->hasMany(Review::class);
    }

    public function collections()
    {
        return $this->hasMany(Collection::class);
    }

    public function borrowedBooks()
    {
        return $this->belongsToMany(Borrow::class, 'borrowed_book')->withPivot('status', 'tanggal_peminjaman', 'tanggal_pengembalian');
    }

    // public function isBorrowed($userId)
    // {
    //     return $this->borrows()->where('user_id', $userId)->where('status', 'borrowed')->exists();
    // }

    public function isInCollection($userId)
    {
        return $this->collections()->where('user_id', $userId)->exists();
    }
    
}
