@extends('layouts.main', ['class' => 'g-sidenav-show bg-gray-100'])
@section('title', '- Awards')
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Awards'])
    <body class="antialiased">
        <div id="awards">
            <awards></awards>
        </div>
        @include('layouts.footers.auth.footer')
    </body>
@endsection
    
