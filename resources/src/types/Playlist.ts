import { Song } from './Song';

export interface Playlist {
  id: number;
  slug: string;
  songs_count: number;
  url: string | null;
  songs?: Song[];
}

export interface playlistsHttpResponse {
  data: Playlist[];
}

export interface playlistHttpResponse {
  data: Playlist;
}

export interface SpotifyPlaylist {
  id: number;
  spotify_id: string;
  snapshot_id: string;
  url: string;
  songs_count: number;
  recently_created: boolean;
}

export interface playlistSyncHttpResponse {
  data: {
    synced_songs: number;
    spotify_playlist: SpotifyPlaylist;
  };
}

export type PlaylistSync = playlistSyncHttpResponse['data'];
