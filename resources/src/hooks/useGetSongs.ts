import { QueryKey, useQuery, useQueryClient } from '@tanstack/react-query';
import { AxiosError } from 'axios';
import songs, { getSongsResponse } from '@/utils/api/songs';
import { slugify } from '@/utils/system/string';

const QUERY_KEY_SONGS = 'songs';

export function getQueryKeyList(
  playlistId: string,
  page: number,
  term: string | null,
): QueryKey {
  let keys = [QUERY_KEY_SONGS, playlistId, page];

  if (term !== null && term != '') {
    keys.push(slugify(term));
  }

  return keys;
}

export function useGetSongs(playlistId: string, page = 1, term: string | null) {
  const queryClient = useQueryClient();
  const query = useQuery<getSongsResponse, AxiosError>({
    queryKey: getQueryKeyList(playlistId, page, term),
    queryFn: ({ signal }) => songs.get(playlistId, page, { signal, term }),
    onSettled: (data) => {
      if (data?.meta.current_page != data?.meta.last_page) {
        queryClient.prefetchQuery(
          getQueryKeyList(playlistId, page + 1, term),
          ({ signal }) => songs.get(playlistId, page + 1, { signal, term }),
        );
      }
    },
  });

  return query;
}
