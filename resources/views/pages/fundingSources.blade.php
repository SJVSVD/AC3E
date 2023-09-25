@extends('layouts.main', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Funding Sources'])
    <body class="antialiased">
        <div id="funding-sources">
            <funding-sources></funding-sources>
        </div>
        @include('layouts.footers.auth.footer')
    </body>
@endsection
    
