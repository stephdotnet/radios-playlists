import { useEffect } from 'react';
import { QueryKey, useQuery, useQueryClient } from '@tanstack/react-query';
import { AxiosError } from 'axios';
import songs, {
  GetSongsResponse,
  SongsRequestOptions,
} from '@/utils/api/songs';
import { slugify } from '@/utils/system/string';

const QUERY_KEY_SONGS = 'songs';

export function getQueryKeyList(
  playlistId: string,
  page: number,
  term: string | null,
  sort: SongsRequestOptions['sort'],
): QueryKey {
  const keys = [QUERY_KEY_SONGS, playlistId, page, sort];

  if (term !== null && term != '') {
    keys.push(slugify(term));
  }

  return keys;
}

export function useGetSongs(playlistId: string, page = 1, term: string | null) {
  const queryClient = useQueryClient();
  const sort = {
    field: 'created_at',
    direction: '-',
  } as const;

  const query = useQuery<GetSongsResponse, AxiosError>({
    queryKey: getQueryKeyList(playlistId, page, term, sort),
    queryFn: ({ signal }) =>
      songs.get(playlistId, page, { signal, term, sort }),
  });

  useEffect(() => {
    if (query.data?.meta.current_page != query.data?.meta.last_page) {
      queryClient.prefetchQuery(
        getQueryKeyList(playlistId, page + 1, term, sort),
        ({ signal }) => songs.get(playlistId, page + 1, { signal, term, sort }),
      );
    }
  }, [query.isFetched]);

  return query;
}
