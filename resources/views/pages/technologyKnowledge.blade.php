@extends('layouts.main', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Technology and knowledge transfer'])
    <body class="antialiased">
        <div id="technology-knowledge">
            <technology-knowledge></technology-knowledge>
        </div>
        @include('layouts.footers.auth.footer')
    </body>
@endsection
    
