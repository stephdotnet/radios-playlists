import { QueryKey, useQuery } from '@tanstack/react-query';
import { AxiosError } from 'axios';
import { Playlist } from '@/types/Playlist';
import playlists from '@/utils/api/playlists';

const QUERY_KEY_PLAYLISTS = 'playlists';
const QUERY_KEY_PLAYLIST = 'playlist';

export function getQueryKeyList(page?: number): QueryKey {
  if (page === undefined) {
    return [QUERY_KEY_PLAYLISTS];
  }
  return [QUERY_KEY_PLAYLISTS, page];
}

export function getQueryKeyShow(id?: string): QueryKey {
  if (id === undefined) {
    return [QUERY_KEY_PLAYLIST];
  }
  return [QUERY_KEY_PLAYLIST, id];
}

export function useGetPlaylists() {
  return useQuery<Playlist[], AxiosError>(getQueryKeyList(1), ({ signal }) =>
    playlists.get(1, { signal }),
  );
}

export function useShowPlaylist(id: string) {
  return useQuery<Playlist, AxiosError>(getQueryKeyShow(id), ({ signal }) =>
    playlists.show(id, { signal }),
  );
}
