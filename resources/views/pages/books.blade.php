@extends('layouts.main', ['class' => 'g-sidenav-show bg-gray-100'])
@section('title', '- Books')
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Books'])
    <body class="antialiased">
        <div id="books">
            <books></books>
        </div>
        @include('layouts.footers.auth.footer')
    </body>
@endsection
    
