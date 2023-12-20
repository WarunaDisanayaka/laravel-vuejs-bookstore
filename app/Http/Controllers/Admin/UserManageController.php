<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;


class UserManageController extends Controller
{
    //

    public function index()
    {
        $users = User::get();
        $stores = Store::all();

        return Inertia::render(
            'Admin/User/Index',
            [
                'users' => $users,
                'stores'=>$stores
            ]
        );
    }

    public function addUser(Request $request)
    {
        // Validate the request data for adding a user
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'userRole' => 'required|in:1,2', // Adjust the numeric values as needed
            'storeId' => 'nullable|exists:stores,id', // Validate store_id against the 'stores' table

        ]);

        // Create a new user
        $user = new User();
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->password = Hash::make($validatedData['password']);
        $user->isAdmin = $validatedData['userRole'] == 1; // Check against the numeric value
        $user->store_id = $validatedData['storeId']; // Set the store_id

        $user->save();

        // Redirect or respond as needed
        return redirect()->route('admin.users.index')->with('success', 'User added successfully');
    }

}
