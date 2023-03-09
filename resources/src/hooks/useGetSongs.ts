import { QueryKey, useQuery } from '@tanstack/react-query';
import { AxiosError } from 'axios';
import { Song } from '@/types/Song';
import songs from '@/utils/api/songs';

const QUERY_KEY_SONGS = 'songs';

export function getQueryKeyList(playlistId: string, page?: number): QueryKey {
  if (page === undefined) {
    return [QUERY_KEY_SONGS, playlistId];
  }
  return [QUERY_KEY_SONGS, playlistId, page];
}

export function useGetSongs(playlistId: string, page?: number) {
  return useQuery<Song[], AxiosError>(getQueryKeyList(playlistId, page), ({ signal }) =>
    songs.get(playlistId, page, { signal }),
  );
}
