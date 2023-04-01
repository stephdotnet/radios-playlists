import { QueryKey, useQuery } from '@tanstack/react-query';
import { AxiosError } from 'axios';
import { Me } from '@/types/Me';
import me from '@/utils/api/me';

const QUERY_KEY = 'me';

export function getQueryKey(): QueryKey {
  return [QUERY_KEY];
}

export function useGetMe() {
  return useQuery<Me, AxiosError>(getQueryKey(), ({ signal }) =>
    me.get({ signal }),
  );
}
