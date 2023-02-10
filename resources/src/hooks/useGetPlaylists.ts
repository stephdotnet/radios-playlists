import { QueryKey, useQuery } from '@tanstack/react-query';
import { AxiosError } from 'axios';
import { Playlist } from '@/types/Playlist';
import playlists from '@/utils/api/playlists';

const QUERY_KEY = 'playlists';

export function getQueryKey(page?: number): QueryKey {
  if (page === undefined) {
    return [QUERY_KEY];
  }
  return [QUERY_KEY, page];
}

export function useGetPlaylists() {
  return useQuery<Playlist[], AxiosError>(getQueryKey(1), ({ signal }) => playlists.get(1, { signal }));
}

export function useShowPlaylist(id: string) {
  return useQuery<Playlist, AxiosError>(getQueryKey(), ({ signal }) => playlists.show(id, { signal }));
}
