<?php
namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection, WithMapping, WithHeadings
{
    public function collection()
    {
        return Order::with(['userAddress.user', 'userAddress', 'orderItems.book'])->get();
    }

    public function map($order): array
    {
        return [
            'Order ID' => $order->id,
            'Total Price' => $order->total_price,
            'Status' => $order->status,
            'Session ID' => $order->session_id,
            'User Name' => $order->userAddress->user->name, // Add user details
            'User Address' => $order->userAddress->address1,
            // Add more columns as needed
        ];
    }

    public function headings(): array
    {
        return [
            'Order ID',
            'Total Price',
            'Status',
            'Session ID',
            'User Name',
            'User Address',
            // Add more headings as needed
        ];
    }
}
