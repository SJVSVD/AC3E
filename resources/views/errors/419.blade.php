@extends('layouts.main', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'ERROR'])
    <body class="antialiased">
        <div id="error">
            <error-component></error-component>
        </div>
        @include('layouts.footers.auth.footer')
    </body>
@endsection