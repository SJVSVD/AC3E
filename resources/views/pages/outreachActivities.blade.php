@extends('layouts.main', ['class' => 'g-sidenav-show bg-gray-100'])
@section('title', '- Dissemination and Outreach Activities')
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Dissemination and Outreach Activities'])
    <body class="antialiased">
        <div id="outreach-activities">
            <outreach-activities></outreach-activities>
        </div>
        @include('layouts.footers.auth.footer')
    </body>
@endsection
    
