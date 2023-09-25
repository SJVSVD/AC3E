@extends('layouts.main', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Public-private connections'])
    <body class="antialiased">
        <div id="public-private">
            <public-private></public-private>
        </div>
        @include('layouts.footers.auth.footer')
    </body>
@endsection
    
