@extends('layouts.main', ['class' => 'g-sidenav-show bg-gray-100'])
@section('title', '- Power bi Goals')
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Power bi Goals'])
    <body class="antialiased">
        <div id="powerbi-goals">
            <powerbi-goals></powerbi-goals>
        </div>
        @include('layouts.footers.auth.footer')
    </body>
@endsection
    
