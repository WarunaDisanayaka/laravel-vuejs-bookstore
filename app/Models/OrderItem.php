<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'book_id', 'quantity', 'unit_price'];

    use HasFactory;
    function order()  {
        return $this->belongsTo(Order::class,'order_id');
    }
    public function product()
    {
        return $this->belongsTo(Book::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class,'book_id');
    }
}
