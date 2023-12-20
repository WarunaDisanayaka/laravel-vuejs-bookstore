<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ManagerController extends Controller
{
    public function view()
    {

        $isAdmin= session('isAdmin');
        // Use $store_id as needed in your admin dashboard logic

        return Inertia::render('Manager/Dashboard', ['isAdmin' => $isAdmin]);
    }


    public function index()
    {
        $store_id = session('admin_store_id');
        $isAdmin = session('isAdmin');

        $products = Book::with('product_images')->get();

        return Inertia::render(
            'Manager/Book/Index',
            [
                'products' => $products,
                'store_id' => $store_id,
                'isAdmin'=>$isAdmin
            ]
        );
    }

    public function store(Request $request)
    {
        $product = new Book;
        $product->title = $request->title;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->description = $request->description;
        $product->isbn = $request->isbn;
        $product->publisher = $request->publisher;
        $product->edition = $request->edition;
        $product->number_of_pages = $request->number_of_pages;
        $product->store_id = $request->store_id;
        $product->save();

        // Check if product has images upload
        if ($request->hasFile('product_images')) {
            $productImages = $request->file('product_images');
            foreach ($productImages as $image) {
                $uniqueName = time() . '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->move('product_images', $uniqueName);
                ProductImage::create([
                    'book_id' => $product->id,
                    'image' => 'product_images/' . $uniqueName,
                ]);
            }
        }

        return redirect()->route('manager.products.index')->with('success', 'Book created successfully.');
    }

    public function update(Request $request, $id)
    {
        $product = Book::findOrFail($id);
        $product->title = $request->title;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->description = $request->description;
        $product->isbn = $request->isbn;
        $product->publisher = $request->publisher;
        $product->edition = $request->edition;
        $product->number_of_pages = $request->number_of_pages;

        // Check if product images were uploaded
        if ($request->hasFile('product_images')) {
            $productImages = $request->file('product_images');
            foreach ($productImages as $image) {
                $uniqueName = time() . '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->move('product_images', $uniqueName);
                ProductImage::create([
                    'book_id' => $product->id,
                    'image' => 'product_images/' . $uniqueName,
                ]);
            }
        }

        $product->update();
        return redirect()->route('manager.products.index')->with('success', 'Book updated successfully.');
    }

    public function deleteImage($id)
    {
        ProductImage::where('id', $id)->delete();
        return redirect()->route('manager.products.index')->with('success', 'Image deleted successfully.');
    }

    public function destroy($id)
    {
        Book::findOrFail($id)->delete();
        return redirect()->route('manager.products.index')->with('success', 'Book deleted successfully.');
    }
}
