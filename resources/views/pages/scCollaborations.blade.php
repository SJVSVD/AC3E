@extends('layouts.main', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Visits and Stays'])
    <body class="antialiased">
        <div id="sc-collaborations">
            <sc-collaborations></sc-collaborations>
        </div>
        @include('layouts.footers.auth.footer')
    </body>
@endsection
    
