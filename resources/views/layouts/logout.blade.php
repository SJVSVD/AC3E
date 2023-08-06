<!DOCTYPE html>
<html lang="en">
   
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>
            AC3E - Reporting System
        </title>
        <!-- Font Awesome Icons -->
        <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
        <link rel="icon" href="{{ asset('AC3E.png') }}" type="image/x-icon">

        <!-- CSS Files -->
        @vite('resources/css/app.css')
        @vite('resources/css/datatables.css')
        <link id="pagestyle" href="{{asset('assets/css/argon-dashboard.css')}}" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/vue@2.7.14/dist/vue.js"></script>
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>   
        <!-- Js Files -->
        @vite('resources/js/app.js')
</head>

<body class="{{ $class ?? '' }}">

    @guest
        @yield('content')
    
    @endguest

    @auth
        @if (in_array(request()->route()->getName(), ['login', 'recover-password', 'rtl']))
            @yield('content')
        @else
            @if (!in_array(request()->route()->getName(), ['profile']))
                <div class="min-height-300 bg-primary position-absolute w-100"></div>
            @elseif (in_array(request()->route()->getName(), ['profile']))
                <div class="position-absolute w-100 min-height-300 top-0" style="background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/profile-layout-header.jpg'); background-position-y: 50%;">
                    <span class="mask bg-primary opacity-6"></span>
                </div>
            @endif
                <main class="main-content border-radius-lg">
                    @yield('content')
                </main>
            @include('components.fixed-plugin')
        @endif
    @endauth
    </div>
    <!--   Core JS Files   -->
    <script src="{{asset('assets/js/core/popper.min.js')}}"></script>
    <script src="{{asset('assets/js/core/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/js/plugins/perfect-scrollbar.min.js')}}"></script>
    <script src="{{asset('assets/js/plugins/smooth-scrollbar.min.js')}}"></script>

</body>

</html>
