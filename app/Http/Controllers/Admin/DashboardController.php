<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard
     */
    public function index()
    {
        $adminCount = User::where('role', 'admin')->count();
        $menuItems = Food::count();

        return view('admin.dashboard', compact(
            'adminCount',
            'menuItems'
        ));
    }
}
