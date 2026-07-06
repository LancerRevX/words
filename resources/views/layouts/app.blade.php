<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nikita Kirenkov's Words</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-orange-100">
    <a href="{{ route('home') }}" class="flex justify-center items-center border-b-2 border-orange-600 bg-orange-400 text-white font-bold text-xl py-4">
        <h1>Nikita Kirenkov's Words</h1>
    </a>
    {{ $slot }}
    @livewireScripts
</body>
</html>