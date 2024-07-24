@extends('layouts.main', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Intellectual Property'])
    <body class="antialiased">
        <div id="patents">
            <patents></patents>
        </div>
        @include('layouts.footers.auth.footer')
    </body>
@endsection
    
