import { QueryKey, useQuery } from '@tanstack/react-query';
import { AxiosError } from 'axios';
import { Playlist, PlaylistSyncCount } from '@/types/Playlist';
import playlists from '@/utils/api/playlists';

const QUERY_KEY_PLAYLISTS = 'playlists';
const QUERY_KEY_PLAYLIST = 'playlist';
const QUERY_KEY_SYNC_COUNT = 'sync-count';

/*
|--------------------------------------------------------------------------
| List
|--------------------------------------------------------------------------
*/

export function getQueryKeyList(page?: number): QueryKey {
  if (page === undefined) {
    return [QUERY_KEY_PLAYLISTS];
  }
  return [QUERY_KEY_PLAYLISTS, page];
}

export function useGetPlaylists() {
  return useQuery<Playlist[], AxiosError>(getQueryKeyList(1), ({ signal }) =>
    playlists.get(1, { signal }),
  );
}

/*
|--------------------------------------------------------------------------
| Show
|--------------------------------------------------------------------------
*/

export function getQueryKeyShow(id?: string): QueryKey {
  if (id === undefined) {
    return [QUERY_KEY_PLAYLIST];
  }
  return [QUERY_KEY_PLAYLIST, id];
}

export function useShowPlaylist(id: string) {
  return useQuery<Playlist, AxiosError>(getQueryKeyShow(id), ({ signal }) =>
    playlists.show(id, { signal }),
  );
}

/*
|--------------------------------------------------------------------------
| Sync count
|--------------------------------------------------------------------------
*/

export function getQueryKeySyncCount(id?: string): QueryKey {
  return [QUERY_KEY_PLAYLIST, id, QUERY_KEY_SYNC_COUNT];
}

export function useSyncCountPlaylist(id: string) {
  return useQuery<PlaylistSyncCount, AxiosError>(
    getQueryKeySyncCount(id),
    ({ signal }) => playlists.syncCount(Number.parseInt(id), { signal }),
    {
      cacheTime: Infinity,
    },
  );
}
