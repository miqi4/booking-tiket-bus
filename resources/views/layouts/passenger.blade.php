<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'PO. Akas' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-on-background font-body text-body antialiased min-h-screen flex flex-col">
    @include('passenger.partials.nav')
    @if (session('success') || session('error'))
        <div class="max-w-container-max mx-auto w-full px-gutter pt-sm">
            <div class="rounded-lg border px-sm py-sm {{ session('success') ? 'bg-secondary-container text-on-secondary-container border-secondary-container' : 'bg-error-container text-on-error-container border-error-container' }}">
                {{ session('success') ?? session('error') }}
            </div>
        </div>
    @endif
    {{ $slot }}
    @include('passenger.partials.footer')
    @stack('scripts')
</body>
</html>
