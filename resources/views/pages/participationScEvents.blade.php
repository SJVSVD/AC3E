@extends('layouts.main', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Participation of Sc Events'])
    <body class="antialiased">
        <div id="participation-sc-events">
            <participation-sc-events></participation-sc-events>
        </div>
        @include('layouts.footers.auth.footer')
    </body>
@endsection
    
