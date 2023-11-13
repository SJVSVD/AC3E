<?php

namespace App\Http\Controllers;
use App\Models\rolesUsers;
use Illuminate\Http\Request;

class roleUserController extends Controller
{
    public function index()
    {
        return rolesUsers::get();
    }
}
