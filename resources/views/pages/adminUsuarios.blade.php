@extends('layouts.main', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Administración Usuarios'])
    <body class="antialiased">
        <div id="admin-usuarios">
            <admin-usuarios></admin-usuarios>
        </div>
        @include('layouts.footers.auth.footer')
    </body>
@endsection
    
