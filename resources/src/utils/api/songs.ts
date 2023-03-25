import { AxiosResponse } from 'axios';
import { Song, songsHttpResponse } from '@/types/Song';
import { HttpMeta } from '@/types/httpMeta';
import { dataGetValue } from '@/utils';
import { apiClient } from './api';

interface SongsRequestOptions {
  page?: number;
  limit?: number;
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

const ENDPOINT = 'playlists';

const get: getSongsFunction = async (playlistId, page, options) => {
  const response: AxiosResponse<songsHttpResponse> = await apiClient.get(
    `${ENDPOINT}/${playlistId}/songs`,
    {
      params: { limit: dataGetValue(options, 'limit', 50), page: page ?? 1 },
      signal: options?.signal,
    },
  );

  return {
    songs: response.data.data,
    meta: response.data.meta,
  };
};

export default {
  get,
};
