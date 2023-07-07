@extends('layouts.logout')

@section('content')
    <div class="container position-sticky z-index-sticky top-0">
        <div class="row">
            <div class="col-12">
                @include('layouts.navbars.navbarLogin')
            </div>
        </div>
    </div>
    <main class="main-content  mt-0">
        <section>
            <div class="page-header min-vh-100">
                <div class="container" style="margin-left:50px;">
                    <div class="row">
                        <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
                            <div class="card card-plain" style="background-color:white;">
                                <div class="card-header pb-0 text-start">
                                    <h4 class="font-weight-bolder">Reestablecer Contraseña</h4>
                                    <p class="mb-0">Ingrese su correo electrónico</p>
                                </div>
                                <div class="card-body">
                                    <form role="form" method="POST" action="{{ route('reset.perform') }}">
                                        @csrf
                                        @method('post')
                                        <div class="flex flex-col mb-3">
                                            <input type="email" name="email" class="form-control form-control-lg" placeholder="Correo Electrónico" value="{{ old('email') }}" aria-label="Email">
                                            @error('email') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                                        </div>
                                        <div class="text-center">
                                            <button class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0">Enviar enlace</button>
                                        </div>
                                    </form>
                                </div>
                                <div id="alert">
                                    @include('components.alert')
                                </div>
                            </div>
                        </div>
                        <div
                            class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
                            <div class="background-size-cover position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden"
                                style="background-image: url('https://media.fashionnetwork.com/m/0eab/f139/6957/8f57/71f4/a0e0/115d/14e1/75a2/2073/2073.jpg');">
                                <span class="mask bg-gradient-primary opacity-6"></span>
                                <h2 class="mt-5 text-white font-weight-bolder position-relative">AC3E</h2>
                                <p class="text-white position-relative">Panel de Administración</p>
                            </div>
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
        display: none;}
</style>