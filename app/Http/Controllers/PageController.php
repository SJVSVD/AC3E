<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index(string $page)
    {
        if (view()->exists("pages.$page")) {
            return view("pages.$page");
        }
        return abort(404);
    }


    public function adminUsuarios()
    {
        return view('pages.adminUsuarios');
    }


    public function adminRoles()
    {
        return view('pages.adminRoles');
    }


    public function dashboard()
    {
        return view('pages.dashboard');
    }

    
    public function userProfile()
    {
        return view('pages.userProfile');
    }

    public function isiPublications()
    {
        return view('pages.isiPublications');
    }
}
