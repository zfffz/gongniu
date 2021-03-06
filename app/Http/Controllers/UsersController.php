<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends CommonsController
{
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }
}
