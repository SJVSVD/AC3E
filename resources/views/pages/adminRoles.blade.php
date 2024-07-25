@extends('layouts.main', ['class' => 'g-sidenav-show bg-gray-100'])
@section('title', '- Administration Roles')
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Administration Roles'])
    <body class="antialiased">
        <div id="admin-roles">
            <admin-roles></admin-roles>
        </div>
        @include('layouts.footers.auth.footer')
    </body>
@endsection
    
