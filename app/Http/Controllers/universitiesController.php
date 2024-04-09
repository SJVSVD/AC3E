<?php

namespace App\Http\Controllers;

use App\Models\universities;
use Illuminate\Http\Request;

class UniversitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return universities::get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\universities  $universities
     * @return \Illuminate\Http\Response
     */
    public function show(universities $universities)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\universities  $universities
     * @return \Illuminate\Http\Response
     */
    public function edit(universities $universities)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\universities  $universities
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, universities $universities)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\universities  $universities
     * @return \Illuminate\Http\Response
     */
    public function destroy(universities $universities)
    {
        //
    }
}
