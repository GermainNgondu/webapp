<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ? $title . ' - ' . $layout->getBrand()->name : $layout->getBrand()->name }}</title>
    <link rel="icon" href="/core/files/images/favicon.ico" sizes="any">
    <link rel="apple-touch-icon" href="/core/files/images/favicon.ico">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
    @livewireStyles
</head>