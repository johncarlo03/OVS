<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminAccController extends Controller
{
     public function index()
{
    $admins = Admin::all(); // Fetch all admins from the database
    return view('your-admin-view-name', compact('admins')); // Replace with actual view name
}
}
