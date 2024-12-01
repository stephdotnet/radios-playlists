import { Song } from './Song';

export interface Playlist {
  id: number;
  slug: string;
  name: string;
  songs_count: number;
  songs_to_sync: number;
  active: boolean;
  url: string | null;
  songs?: Song[];
}

export interface PlaylistsHttpResponse {
  data: Playlist[];
}

export interface PlaylistHttpResponse {
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

export interface PlaylistSyncHttpResponse {
  data: {
    synced_songs: number;
    spotify_playlist: SpotifyPlaylist;
  };
}

export type PlaylistSync = PlaylistSyncHttpResponse['data'];

export type PlaylistStats = {
  [key: string]: {
    total: number;
    count: number;
  };
};

export type PlaylistStatsHttpResponse = PlaylistStats;

/*
|--------------------------------------------------------------------------
| Sync Count
|--------------------------------------------------------------------------
*/

export type PlaylistSyncCount = {
  count: number;
};

export type PlaylistSyncCountHttpResponse = {
  data: PlaylistSyncCount;
};
