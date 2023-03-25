import { useEffect } from 'react';
import { QueryKey, useQuery, useQueryClient } from '@tanstack/react-query';
import { AxiosError } from 'axios';
import songs, { getSongsResponse } from '@/utils/api/songs';

const QUERY_KEY_SONGS = 'songs';

export function getQueryKeyList(playlistId: string, page?: number): QueryKey {
  if (page === undefined) {
    return [QUERY_KEY_SONGS, playlistId];
  }
  return [QUERY_KEY_SONGS, playlistId, page];
}

export function useGetSongs(playlistId: string, page = 1) {
  const query = useQuery<getSongsResponse, AxiosError>(
    getQueryKeyList(playlistId, page),
    ({ signal }) => songs.get(playlistId, page, { signal }),
  );

  const queryClient = useQueryClient();
  useEffect(() => {
    if (query.data?.meta.current_page != query.data?.meta.last_page) {
      queryClient.prefetchQuery(
        getQueryKeyList(playlistId, page + 1),
        ({ signal }) => songs.get(playlistId, page + 1, { signal }),
      );
    }
  }, [query.data, page, queryClient]);

  return query;
}
