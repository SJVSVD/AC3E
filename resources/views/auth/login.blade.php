@extends('layouts.logout')

@section('content')
    <div class="container position-sticky z-index-sticky top-0">
        <div class="row">
            <div class="col-12">
                @include('layouts.navbars.navbarLogin')
            </div>
        </div>
    </div>
    <main class="main-content mt-0">
        <section>
            <div class="page-header min-vh-100 ">
                <div class="container" >
                    <div class="row">
                        <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
                            <div class="card card-plain" style="background-color:white; ">
                                <div class="card-header pb-0 text-start">
                                    <h4 class="font-weight-bolder">Login</h4>
                                </div>
                                <div class="card-body">
                                    <form role="form" method="POST" action="{{ route('login.perform') }}">
                                        @csrf
                                        @method('post')
                                        <div class="flex flex-col mb-3">
                                            <input placeholder="Email" type="email" name="email" class="form-control form-control-lg" aria-label="Email">
                                            @error('email') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                                        </div>
                                        <div class="flex flex-col mb-3">
                                            <input id="passwordInput" placeholder="Password" type="password" name="password" class="form-control form-control-lg" aria-label="Password">
                                            @error('password') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="rememberMe" name="rememberMe" @if(old('rememberMe')) checked @endif>
                                                <label class="form-check-label" for="rememberMe">Remember Me</label>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="passwordVisibility" onclick="myFunction()">
                                                <label class="form-check-label" for="passwordVisibility">Show Password</label>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <button class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0">Login</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px">
                            @if ($message = Session::get('success'))
                                <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
                                    <div class="card card-plain" style="background-color:#5CB85C;">
                                        <div class="card-body">
                                            <p style="color: white">{{ $message }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($message = Session::get('error'))
                                <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
                                    <div class="card card-plain" style="background-color:#D2504D;">
                                        <div class="card-body">
                                            <p style="color: white">{{ $message }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @include('layouts.footers.auth.footer')
    </main>
@endsection

<style>
    #sidebarMain{
        display: none;
    }

    .row{
        flex-direction:column; 
        align-items:center;
    }
</style>

<script>
    function myFunction() {
    var x = document.getElementById("passwordInput");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
    }
</script>