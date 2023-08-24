@extends('layouts.main', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Organization of Sc Events'])
    <body class="antialiased">
        <div id="organization-sc-events">
            <organization-sc-events></organization-sc-events>
        </div>
        @include('layouts.footers.auth.footer')
    </body>
@endsection
    
