import { HttpMeta } from './httpMeta';

export interface Song {
  id: number;
  name: string;
  artists: string;
  spotify_url: string;
  created_at: string;
}

export interface songsHttpResponse {
  data: Song[];
  meta: HttpMeta;
}
