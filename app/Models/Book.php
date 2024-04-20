<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
class Book extends Model
{
    use HasFactory, SoftDeletes, HasRoles;
    protected $fillable = ['title', 'author', 'isbn', 'description', 'book_type', 'language', 'publisher', 'location', 'instock', 'cost'];

}
