<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        {{--    <title>{{ config('app.name', 'Facturación Electrónica') }}</title>--}}
        <title>{{ $vc_company->trade_name }}</title>

        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

        <link rel="stylesheet" href="{{ asset('porto-light/vendor/bootstrap/css/bootstrap.css') }}" />
        <link rel="stylesheet" href="{{ asset('porto-light/vendor/animate/animate.css') }}" />
        <link rel="stylesheet" href="{{ asset('porto-light/vendor/font-awesome/css/fontawesome-all.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('porto-light/css/theme.css') }}" />
        <link rel="stylesheet" href="{{ asset('css/auth.css') }}" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.26.29/sweetalert2.min.css" />
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
        @if (file_exists(public_path('theme/custom_styles.css')))
            <link rel="stylesheet" href="{{ asset('theme/custom_styles.css') }}" />
        @endif
    </head>
    <body>
        <div class="app">
            @yield('content')
        </div>
        @stack('scripts')
    </body>
</html>
