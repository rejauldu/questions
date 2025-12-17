<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'ExamDao') }}</title>
    
    <!-- Scripts -->
    @routes
    @vite(['resources/css/app.css', 'resources/js/chatbot.js'], 'build/chatbot')
    @inertiaHead
</head>
<body>
    @inertia
</body>
</html>