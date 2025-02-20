@extends('layouts.main', ['class' => 'g-sidenav-show bg-gray-100'])
@section('title', '- Performance Results')
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Performance Results'])
        <div id="powerbitest-component">
            <powerbitest-component></powerbitest-component>
        </div>
    @include('layouts.footers.auth.footer')
@endsection
