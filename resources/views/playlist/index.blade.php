<x-layout>
    <x-slot name="title">Playlists</x-slot>
    <x-slot name="content">
        <h1>Playlists</h1>
        <ul>
            @foreach ($playlists as $playlist)
            <li>
                <a href="{{ route('playlist.show', $playlist) }}">{{ $playlist->slug }}</a>
            </li>
            @endforeach
        </ul>
    </x-slot>
</x-layout>