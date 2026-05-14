<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" >
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Add inside <head> -->
        <title inertia>{{ config('app.name', 'Laravel') }}</title>
<script src="https://tweakcn.com/live-preview.min.js"></script>
    @vite('resources/js/app.js')
    @inertiaHead
</head>
<body>
    @inertia
</body>
</html>
