@extends('layouts.main', ['class' => 'g-sidenav-show bg-gray-100'])
@section('title', '- Non WoS publications')
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Non WoS publications'])
    <body class="antialiased">
        <div id="non-isi-publications">
            <non-isi-publications></non-isi-publications>
        </div>
        @include('layouts.footers.auth.footer')
    </body>
@endsection
    
