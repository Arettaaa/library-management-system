<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'address',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function books()
    {
        return $this->belongsToMany(Book::class, 'borrows', 'user_id', 'book_id');
    }


    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }

    public function review()
    {
        return $this->hasMany(Review::class);
    }

    public function collections()
    {
        return $this->hasMany(Collection::class);
    }

}

