<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['total_price', 'status', 'session_id', 'user_address_id', 'created_by', 'updated_by'];

    use HasFactory;

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function userAddress()
    {
        return $this->belongsTo(UserAddress::class, 'user_address_id');
    }

    // Remove the books() method from here
}
