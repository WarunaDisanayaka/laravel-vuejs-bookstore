<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminStoreController extends Controller
{
    //

    public function index()
    {
        $stores = Store::all();  // Fetch all stores from the database

        return Inertia::render('Admin/Stores/Index', [
            'stores' => $stores,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:stores|max:255',
            // Add other validation rules as needed
        ]);

        Store::create([
            'name' => $request->input('name'),
            // Add other fields as needed
        ]);

        return redirect()->route('admin.stores.index')->with('success', 'Store added successfully');
    }
}
