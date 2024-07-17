@extends('layouts.main', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Cumulative Results until April, 2024'])
        <div id="powerbi-component">
            <powerbi-component></powerbi-component>
        </div>
    @include('layouts.footers.auth.footer')
@endsection
