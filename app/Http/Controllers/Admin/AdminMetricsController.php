<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class AdminMetricsController extends Controller
{
    public function mostExpensiveBook()
    {
        $mostExpensiveBooks = Book::orderByDesc('price')->get();

        return response()->json(['most_expensive_books' => $mostExpensiveBooks]);
//        dd($mostExpensiveBook);
    }

    public function mostPopularBooksByStore()
    {
        $mostPopularBooksByStore = OrderItem::select('book_id', 'store_id')
            ->with(['book', 'store'])
            ->groupBy('book_id', 'store_id')
            ->orderByDesc(DB::raw('COUNT(*)'))
            ->get();

        return response()->json(['most_popular_books_by_store' => $mostPopularBooksByStore]);
    }

    public function mostBoughtBook()
    {
        $mostBoughtBooks = OrderItem::select('book_id', DB::raw('COUNT(*) as count'))
            ->with('book:id,title') // Load the related book with only id and title
            ->groupBy('book_id')
            ->orderByDesc('count')
            ->get();

        // Transform the result to include count and title for each book
        $result = $mostBoughtBooks->map(function ($item) {
            return [
                'count' => $item->count,
                'title' => optional($item->book)->title,
            ];
        });

        return response()->json(['most_bought_books' => $result]);
    }









    public function leastPreferredBook()
    {
        $leastPreferredBook = OrderItem::select('book_id')
            ->groupBy('book_id')
            ->orderBy(DB::raw('COUNT(*)'))
            ->first();

        return response()->json(['least_preferred_book' => $leastPreferredBook]);
    }
}
