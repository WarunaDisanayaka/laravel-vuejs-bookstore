<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    public function index()
    {
//        $users = User::get();

        return Inertia::render(
            'Admin/Roles/Index',
            [
//                'users' => $users,
            ]
        );
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
//        $request->validate([
//            'name' => 'required|unique:roles|max:255',
//        ]);

        $role = new Role([
            'name' => $request->input('userRole'),
        ]);

        $role->save();

        // You can also associate this role with users or perform any other necessary actions here

        return redirect()->route('admin.roles.index')->with('success', 'Role added successfully');
    }
}
