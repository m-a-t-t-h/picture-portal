<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<title>{{ config('app.name') }}</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}"/>
<link rel="manifest" href="manifest.json">
@vite(['resources/js/app.js', "resources/css/app.css"])
    <!-- Privacy-friendly analytics by Plausible -->
    <script async src="https://p3x-233.base10.webdevops.uk/js/pa-CnJwZWQ4355ZPO5UVhkEp.js"></script>
    <script>
        window.plausible=window.plausible||function(){(plausible.q=plausible.q||[]).push(arguments)},plausible.init=plausible.init||function(i){plausible.o=i||{}};
        plausible.init()
    </script>

</head>
<body><div id="app_root"></div></body>
</html>
