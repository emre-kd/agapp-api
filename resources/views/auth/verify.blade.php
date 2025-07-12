@extends('auth.layout')


@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Email Adresini Doğrula') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('E-posta adresinize yeni bir doğrulama bağlantısı gönderildi.') }}
                        </div>
                    @endif

                    {{ __('Devam etmeden önce lütfen doğrulama bağlantısı için e-postanızı kontrol edin.') }}
                    {{ __('E-postayı almadıysanız') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('tekrar göndermek için burayı tıklayın') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
