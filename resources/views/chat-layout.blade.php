<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Exam Date Chatbot</title>
        
        <!-- Vite/CSS/JS Assets are still required for Tailwind and Vue -->
        <!-- Vite + CSS -->
        @vite(['resources/css/app.css', 'resources/js/chatbot.js'], 'build/chatbot')
    </head>
    <body class="bg-gray-50 antialiased">
        <!-- This is the Inertia Root directive, now inside the new layout -->
        @inertia
    </body>
</html>