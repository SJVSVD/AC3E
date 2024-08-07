@extends('layouts.main', ['class' => 'g-sidenav-show bg-gray-100'])
@section('title', '- WoS Publications')
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'WoS Publications'])
    <body class="antialiased">
        <div id="isi-publications">
            <isi-publications></isi-publications>
        </div>
        @include('layouts.footers.auth.footer')
    </body>
@endsection
    
