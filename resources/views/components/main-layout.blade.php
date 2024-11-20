<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle }}</title>
    <!-- favicon -->
    <link rel="icon" href=" {{asset('assets/images/favicon.png')}}" type="image/png">
    <!-- bootstrap -->
    <link rel="stylesheet" href="{{asset('assets/bootstrap/bootstrap.min.css')}}">
    <!-- css -->
    <link rel="stylesheet" href="{{asset('assets/css/main.css')}}">
</head>
<body>

    {{--Com isso será sempre carregado o logo--}}
    <x-logo />


    {{ $slot }}



</body>
    {{--Com isso será sempre carregado o footer--}}
    <x-footer />

    <!-- bootstrap -->
    <script src="{{asset('assets/bootstrap/bootstrap.bundle.min.js')}}"></script>
