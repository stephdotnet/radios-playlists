<x-layout>
    <x-slot name="title">Home</x-slot>
    <x-slot name="content">
        <h1>TEST</h1>

        <a href="{{ route('spotify.redirect', ['redirect' => 'api/spotify/playlists/2/sync']) }}">Log in to Spotify !</a>
    </x-slot>
</x-layout>
