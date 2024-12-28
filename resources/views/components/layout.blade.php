<html>
    <head>
        @php
            $title = "Radios Playlists - Creating playlists from your radios while you sleep";
            $description= "This pre-alpha app's goal is to listen your favorite radios for you, and to make playlists that you can listen later.";
        @endphp
        <!-- Primary Meta Tags -->
        <meta name="title" content="{{ $title }}">
        <meta name="description" content="This pre-alpha app's goal is to listen your favorite radios for you, and to make playlists that you can listen later.">

        <title>{{ $title }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon/favicon.svg') }}">
        <link rel="icon" type="image/png" href="{{ asset('favicon/favicon.png') }}">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ route('index') }}">
        <meta property="og:title" content="{{ $title }}">
        <meta property="og:description" content="{{ $description }}">
        <meta property="og:image" content="{{ asset('images/social.jpg') }}">

        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="{{ route('index') }}">
        <meta property="twitter:title" content="{{ $title }}">
        <meta property="twitter:description" content="{{ $description }}">
        <meta property="twitter:image" content="{{ asset('images/social.jpg') }}">

        <meta name="viewport" content="initial-scale=1, width=device-width" />
        <link
            rel="stylesheet"
            href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"
        />

        @viteReactRefresh
        @vite(['resources/src/index.tsx'])
        <style>
            body {
                background: rgb(18, 18, 18);
            }
        </style>
    </head>
    <body
        {{ $content }}
    </body>
</html>
