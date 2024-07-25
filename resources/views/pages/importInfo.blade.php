@extends('layouts.main', ['class' => 'g-sidenav-show bg-gray-100'])
@section('title', '- Imports Data')
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Imports Data'])
        <div id="import-info">
            <import-info></import-info>
        </div>
    @include('layouts.footers.auth.footer')
@endsection
