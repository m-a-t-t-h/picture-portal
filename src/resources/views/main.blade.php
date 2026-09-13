<!DOCTYPE html>
<!--    Hello world! -->
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<title>{{ config('app.name') }}</title>
<meta name="test" value="testing">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}"/>
<link rel="manifest" href="/manifest.json">
@if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
@vite(['resources/css/app.js', 'resources/js/app.css'])
@endif
</head>

<body><div id="app_root"></div></body>
</html>
