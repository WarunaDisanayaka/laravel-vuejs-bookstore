<?php

namespace App\Http\Controllers\User;

use App\Helper\Cart;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\CartItem;
use App\Models\Book;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    public function view(Request $request, Book $book)
    {

        $user = $request->user();
        if ($user) {
            $cartItems = CartItem::where('user_id', $user->id)->get();
            $userAddress = UserAddress::where('user_id', $user->id)->where('isMain', 1)->first();
            if ($cartItems->count() > 0) {
                return Inertia::render(
                    'User/CartList',
                    [
                        'cartItems' => $cartItems,
                        'userAddress' => $userAddress
                    ]
                );
            }

        }
        else {
            $cartItems = Cart::getCookieCartItems();
            if (count($cartItems) > 0) {
                $cartItems = new CartResource(Cart::getProductsAndCartItems());
                return  Inertia::render('User/CartList', ['cartItems' => $cartItems]);
            } else {
                return redirect()->back();
            }
        }
    }
    public function store(Request $request, Book $product)
    {
        $quantity = $request->post('quantity', 1);
        $user = $request->user();

        // Ensure that the product has enough stock
        if ($product->quantity < $quantity) {
            return redirect()->back()->with('error', 'Not enough stock available.');
        }

        if ($user) {
            $cartItem = CartItem::where(['user_id' => $user->id, 'book_id' => $product->id])->first();
            if ($cartItem) {
                $cartItem->increment('quantity');

            } else {
                CartItem::create([
                    'user_id' => $user->id,
                    'book_id' => $product->id,
                    'quantity' => 1,
                ]);

            }
            // Update the product quantity in the database
            $product->decrement('quantity', $quantity);

        } else {
            $cartItems = Cart::getCookieCartItems();
            $isProductExists = false;
            foreach ($cartItems as $item) {
                if ($item['book_id'] === $product->id) {
                    $item['quantity'] += $quantity;
                    $isProductExists = true;
                    break;
                }
            }

            if (!$isProductExists) {
                $cartItems[] = [
                    'user_id' => null,
                    'book_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                ];
            }
            Cart::setCookieCartItems($cartItems);
        }

        return redirect()->back()->with('success', 'cart added successfully');
    }
    public function update(Request $request, Book $product)
    {
        $quantity = $request->integer('quantity');
        $user = $request->user();
        if ($user) {
            CartItem::where(['user_id' => $user->id, 'book_id' => $product->id])->update(['quantity' => $quantity]);
            // Update the product quantity in the database
//            $product->decrement('quantity', $quantity);
        } else {
            $cartItems = Cart::getCookieCartItems();
            foreach ($cartItems as &$item) {
                if ($item['book_id'] === $product->id) {
                    $item['quantity'] = $quantity;
                    break;
                }
            }
            Cart::setCookieCartItems($cartItems);
        }

        return redirect()->back();
    }
    public function delete(Request $request, Book $product)
    {
        $user = $request->user();
        if ($user) {
            CartItem::query()->where(['user_id' => $user->id, 'book_id' => $product->id])->first()?->delete();
            if (CartItem::count() <= 0) {
                return redirect()->route('home')->with('info', 'your cart is empty');
            } else {
                return redirect()->back()->with('success', 'item removed successfully');
            }
        } else {
            $cartItems = Cart::getCookieCartItems();
            foreach ($cartItems as $i => &$item) {
                if ($item['book_id'] === $product->id) {
                    array_splice($cartItems, $i, 1);
                    break;
                }
            }
            Cart::setCookieCartItems($cartItems);
            if (count($cartItems) <= 0) {
                return redirect()->route('home')->with('info', 'your cart is empty');
            } else {
                return redirect()->back()->with('success', 'item removed successfully');
            }
        }
    }
}
