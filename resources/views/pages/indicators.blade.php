@extends('layouts.main', ['class' => 'g-sidenav-show bg-gray-100'])
@section('title', '- Indicators')
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Indicators'])
        <div id="indicators-component">
            <indicators-component></indicators-component>
        </div>
    @include('layouts.footers.auth.footer')
@endsection
