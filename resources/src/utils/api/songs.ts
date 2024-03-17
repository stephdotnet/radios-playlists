import { AxiosResponse } from 'axios';
import { Song, songsHttpResponse } from '@/types/Song';
import { HttpMeta } from '@/types/httpMeta';
import { dataGetValue } from '@/utils';
import { apiClient } from './api';

interface SongsRequestOptions {
  page?: number;
  limit?: number;
  term?: string | null;
  signal?: AbortSignal;
}

export interface getSongsResponse {
  songs: Song[];
  meta: HttpMeta;
}

interface getSongsFunction {
  (
    playlistId?: string,
    page?: number,
    options?: SongsRequestOptions,
  ): Promise<getSongsResponse>;
}

interface queryParameters {
  limit?: number;
  page?: number;
  filter?: {
    term?: string;
  };
}

const ENDPOINT = 'playlists';

const get: getSongsFunction = async (playlistId, page, options) => {
  const queryParameters: queryParameters = {
    limit: dataGetValue(options, 'limit', 50),
    page: page ?? 1,
  };

  if (dataGetValue(options, 'term')) {
    queryParameters['filter'] = { term: dataGetValue(options, 'term') };
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

interface deleteSongFunction {
  (
    playlistId: string,
    songId: number,
    options?: SongsRequestOptions,
  ): Promise<AxiosResponse>;
}

const remove: deleteSongFunction = async (playlistId, songId) => {
  return apiClient.delete(`${ENDPOINT}/${playlistId}/songs/${songId}`);
};

export default {
  get,
  remove,
};
