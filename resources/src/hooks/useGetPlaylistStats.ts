import { QueryKey, useQuery } from '@tanstack/react-query';
import { AxiosError } from 'axios';
import { PlaylistStats } from '@/types/Playlist';
import playlists from '@/utils/api/playlists';

const QUERY_KEY_PLAYLIST_STATS = 'playlist_stats';

export function getQueryKey(id: number): QueryKey {
  return [QUERY_KEY_PLAYLIST_STATS, id];
}

export function usePlaylistStats(id: number) {
  return useQuery<PlaylistStats, AxiosError>(getQueryKey(id), ({ signal }) =>
    playlists.stats(id, { signal }),
  );
}
