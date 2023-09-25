@extends('layouts.main', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Conjoint projects'])
    <body class="antialiased">
        <div id="conjoint-projects">
            <conjoint-projects></conjoint-projects>
        </div>
        @include('layouts.footers.auth.footer')
    </body>
@endsection
    
