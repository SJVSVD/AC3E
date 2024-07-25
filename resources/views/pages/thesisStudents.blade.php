@extends('layouts.main', ['class' => 'g-sidenav-show bg-gray-100'])
@section('title', '- Thesis Students')
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Thesis Students'])
    <body class="antialiased">
        <div id="thesis-students">
            <thesis-students></thesis-students>
        </div>
        @include('layouts.footers.auth.footer')
    </body>
@endsection
    
