@extends('layouts.main', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Postdoctoral fellows'])
    <body class="antialiased">
        <div id="post-doc">
            <post-doc></post-doc>
        </div>
        @include('layouts.footers.auth.footer')
    </body>
@endsection
    
