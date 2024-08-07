@extends('layouts.main', ['class' => 'g-sidenav-show bg-gray-100'])
@section('title', '- Dashboard')
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Main Page'])
    <div class="container-fluid py-4">
        <div id="dashboard">
            <dashboard-component></dashboard-component>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection

<script src="./assets/js/plugins/chartjs.min.js"></script>