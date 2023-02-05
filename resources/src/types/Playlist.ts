import { Song } from './Song';

export interface Playlist {
  id: number;
  slug: string;
  songs_count: number;
  songs?: Song[];
}

export interface playlistsHttpResponse {
  data: Playlist[];
}

export interface playlistHttpResponse {
  data: Playlist;
}
