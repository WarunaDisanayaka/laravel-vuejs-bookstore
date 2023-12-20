<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function index()
    {

        $isAdmin= session('isAdmin');
        // Use $store_id as needed in your admin dashboard logic

        return Inertia::render('Admin/Dashboard', ['isAdmin' => $isAdmin]);
    }
}
