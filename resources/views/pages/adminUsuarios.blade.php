@extends('layouts.main', ['class' => 'g-sidenav-show bg-gray-100'])
@section('title', '- Administration Users')
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Administration Users'])
    <body class="antialiased">
        <div id="admin-usuarios">
            <admin-usuarios></admin-usuarios>
        </div>
        @include('layouts.footers.auth.footer')
    </body>
@endsection
    
