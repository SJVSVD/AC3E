<?php

namespace App\Http\Controllers;
use App\Models\researchLines;
use Illuminate\Http\Request;

class researchLineController extends Controller
{
    public function index()
    {
        return researchLines::get();
    }
}
