<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{

    function index()
    {
        $orders = Order::with('orderItems.book')->get();

        $formattedOrders = $orders->map(function ($order) {
            $bookNames = $order->orderItems->pluck('book.title')->toArray();
            return [
                'id' => $order->id,
                'total_price' => $order->total_price,
                'status' => $order->status,
                'session_id' => $order->session_id,
                'user_address_id' => $order->user_address_id,
                'created_by' => $order->created_by,
                'updated_by' => $order->updated_by,
                'book_names' => $bookNames,
            ];
        });

        return Inertia::render('User/Dashboard', ['orders' => $formattedOrders]);

    }
}
