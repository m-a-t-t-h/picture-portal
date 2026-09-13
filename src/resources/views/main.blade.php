<!DOCTYPE html>
<!--    Hello world! -->
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<title>{{ config('app.name') }}</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}"/>
<link rel="manifest" href="manifest.json">
@vite(['resources/js/app.js', "resources/css/app.css"])
</head>
<body><div id="app_root"></div></body>
</html>
