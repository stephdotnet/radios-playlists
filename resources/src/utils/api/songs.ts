import { AxiosResponse } from 'axios';
import { Song, songsHttpResponse } from '@/types/Song';
import { HttpMeta } from '@/types/httpMeta';
import { dataGetValue } from '@/utils';
import { apiClient } from './api';

interface SongsRequestOptions {
  page?: number;
  limit?: number;
  term?: string | null;
  sort?: {
    field: string;
    direction: '+' | '-';
  };
  signal?: AbortSignal;
}

export interface GetSongsResponse {
  songs: Song[];
  meta: HttpMeta;
}

interface GetSongsFunction {
  (
    playlistId?: string,
    page?: number,
    options?: SongsRequestOptions,
  ): Promise<GetSongsResponse>;
}

interface QueryParameters {
  limit?: number;
  page?: number;
  sort?: string;
  filter?: {
    term?: string;
  };
}

const ENDPOINT = 'playlists';

const get: GetSongsFunction = async (playlistId, page, options) => {
  const queryParameters: QueryParameters = {
    limit: dataGetValue(options, 'limit', 50),
    page: page ?? 1,
  };

  if (dataGetValue(options, 'term')) {
    queryParameters['filter'] = { term: dataGetValue(options, 'term') };
  }

  if (dataGetValue(options, 'sort')) {
    queryParameters['sort'] =
      dataGetValue(options, 'sort.direction') +
      dataGetValue(options, 'sort.field');
  }

  const response: AxiosResponse<songsHttpResponse> = await apiClient.get(
    `${ENDPOINT}/${playlistId}/songs`,
    {
      params: queryParameters,
      signal: options?.signal,
    },
  );

  return {
    songs: response.data.data,
    meta: response.data.meta,
  };
};

interface DeleteSongFunction {
  (
    playlistId: string,
    songId: number,
    options?: SongsRequestOptions,
  ): Promise<AxiosResponse>;
}

const remove: DeleteSongFunction = async (playlistId, songId) => {
  return apiClient.delete(`${ENDPOINT}/${playlistId}/songs/${songId}`);
};

export default {
  get,
  remove,
};
