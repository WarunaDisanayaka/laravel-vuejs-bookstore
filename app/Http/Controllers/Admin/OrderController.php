<?php

namespace App\Http\Controllers\Admin;

use App\Exports\OrdersExport;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['userAddress.user','userAddress', 'orderItems.book'])->get();
//        dd($orders);

        return Inertia::render('Admin/Orders/Index', [
            'orders' => $orders,

        ]);
    }

    public function export()
    {
        return Excel::download(new OrdersExport, 'orders.xlsx');
    }
}
