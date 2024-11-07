<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; // <-- Add this line

class UserController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param  \App\User  $model
     * @return \Illuminate\View\View
     */
    public function index(User $model)
    {
        // Eager load the relationships for the authenticated user
        $user = Auth::user()->load('miller_admin.miller', 'cooperative');

        return view('users.index', compact('user'));
    }
}