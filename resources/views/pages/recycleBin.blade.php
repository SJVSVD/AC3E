@extends('layouts.main', ['class' => 'g-sidenav-show bg-gray-100'])
@section('title', '- Recycle Bin')
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Recycle Bin'])
        <div id="recycle-bin">
            <recycle-bin></recycle-bin>
        </div>
    @include('layouts.footers.auth.footer')
@endsection
