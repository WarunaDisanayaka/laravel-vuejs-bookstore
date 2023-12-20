<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;
    protected $fillable = [
        'book_id',
        'image',];
    function product()
    {
        return $this->belongsTo(Book::class);
    }
}
