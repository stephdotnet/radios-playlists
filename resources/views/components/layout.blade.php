<html>
    <head>
        <title>{{ $title }}</title>

        <link
            rel="stylesheet"
            href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"
        />

        @viteReactRefresh
        @vite(['resources/src/index.tsx'])
    </head>
    <body>
        {{ $content }}
    </body>
</html>
