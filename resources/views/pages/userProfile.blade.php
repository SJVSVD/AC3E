@extends('layouts.main', ['class' => 'g-sidenav-show bg-gray-100'])
@section('title', '- My Profile')
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'My Profile'])
        <div id="userp">
            <user-profile-component></user-profile-component>
        </div>
    @include('layouts.footers.auth.footer')
@endsection
