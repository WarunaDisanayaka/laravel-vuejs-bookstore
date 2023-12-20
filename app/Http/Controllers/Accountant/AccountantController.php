<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountantController extends Controller
{
    public function view()
    {

        $isAdmin= session('isAdmin');
        // Use $store_id as needed in your admin dashboard logic

        return Inertia::render('Accountant/Dashboard', ['isAdmin' => $isAdmin]);
    }
}
