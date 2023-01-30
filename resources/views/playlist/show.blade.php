<x-layout>
    <x-slot name="title">{{ $playlist->name }}</x-slot>
    <x-slot name="content">
        <h1>{{ $playlist->name }}</h1>
        <a href="{{ route('playlist.index') }}">Retour aux playlists</a>
        <ul>
            @foreach ($playlist->songs as $song)
            <li>
                <a href="{{ data_get($song->data, 'external_urls.spotify') }}" target="_blank">{{ $song->name }} ({{$song->artists}})</a>
            </li>
            @endforeach
        </ul>
    </x-slot>
</x-layout>