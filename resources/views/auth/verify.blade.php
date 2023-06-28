@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verifique su dirección de correo electronico:') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Un email de verificación ha sido enviado a su email.') }}
                        </div>
                    @endif

                    {{ __('Antes de proseguir, por favor revise su email por un enlace de verificación.') }}
                    {{ __('Si no recibió un email') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <a class="btn btn-link p-0 m-0 align-baseline">{{ __('presione aquí para otra solicitud') }}</a>.
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.footers.auth.footer')
</div>
@endsection
