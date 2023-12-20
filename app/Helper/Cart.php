<?php

namespace App\Helper;

use App\Models\CartItem;
use App\Models\Book;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;

class Cart
{
    public static function getCount(): int
    {
        if ($user = auth()->user()) {
            return CartItem::whereUserId($user->id)->count(); //sum('quantity')
        } else {
            return array_reduce(self::getCookieCartItems(), fn ($carry) => $carry + 1, 0);
        }
    }

    public static function getCartItems()
    {
        if ($user = auth()->user()) {
            return CartItem::whereUserId($user->id)->get()->map(fn (CartItem $item) => ['book_id' => $item->book_id, 'quantity' => $item->quantity]);
        } else {
            return self::getCookieCartItems();
        }
    }

    public static function getCookieCartItems()
    {
        return json_decode(request()->cookie('cart_items', '[]'), true);
    }

    public static function setCookieCartItems(array $cartItems)
    {
        Cookie::queue('cart_items', json_encode($cartItems), 60*24*30);
    }

    public static function saveCookieCartItems()
    {
        $user = auth()->user();
        $userCartItems = CartItem::where(['user_id' => $user->id])->get()->keyBy('book_id');
        $savedCartItems = [];
        foreach (self::getCookieCartItems() as $cartItem) {
            if (isset($userCartItems[$cartItem['book_id']])) {
                $userCartItems[$cartItem['book_id']]->update(['quantity' => $cartItem['quantity']]);
                continue;
            }
            $savedCartItems[] = [
                'user_id' => $user->id,
                'book_id' => $cartItem['book_id'],
                'quantity' => $cartItem['quantity'],
            ];
        }
        if (!empty($savedCartItems)) {
            CartItem::insert($savedCartItems);
        }
    }

    public static function moveCartItemsIntoDb()
    {
        $request = request();
        $cartItems = self::getCookieCartItems();
        $newCartItems = [];
        foreach ($cartItems as $cartItem) {
            // Check if the record already exists in the database
            $existingCartItem = CartItem::where([
                'user_id' => $request->user()->id,
                'book_id' => $cartItem['book_id'],
            ])->first();

            if (!$existingCartItem) {
                // Only insert if it doesn't already exist
                $newCartItems[] = [
                    'user_id' => $request->user()->id,
                    'book_id' => $cartItem['book_id'],
                    'quantity' => $cartItem['quantity'],
                ];
            }
        }


        if (!empty($newCartItems)) {
            // Insert the new cart items into the database
            CartItem::insert($newCartItems);
        }
    }


    public static function getProductsAndCartItems()
    {
        $cartItems = self::getCartItems();

        $ids = Arr::pluck($cartItems, 'book_id');
        $products = Book::whereIn('id', $ids)->with('product_images')->get();
        $cartItems = Arr::keyBy($cartItems, 'book_id');
        return [$products, $cartItems];
    }
}
