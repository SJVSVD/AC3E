@extends('layouts.main', ['class' => 'g-sidenav-show bg-gray-100'])
@section('title', '- Cumulative Results until April, 2024')
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Cumulative Results until April, 2024'])
        <div id="powerbitest-component">
            <powerbitest-component></powerbitest-component>
        </div>
    @include('layouts.footers.auth.footer')
@endsection
