@extends('layouts.main', ['class' => 'g-sidenav-show bg-gray-100'])
@section('title', '- Performance Targets')
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Performance Targets'])
    <body class="antialiased">
        <div id="powerbi-goals">
            <powerbi-goals></powerbi-goals>
        </div>
        @include('layouts.footers.auth.footer')
    </body>
@endsection
    
