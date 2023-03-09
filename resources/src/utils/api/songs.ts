import { AxiosResponse } from 'axios';
import { Song, songsHttpResponse } from '@/types/Song';
import { dataGetValue } from '@/utils';
import { apiClient } from './api';

interface SongsRequestOptions {
  page?: number;
  limit?: number;
  signal?: AbortSignal;
}

interface getSongsFunction {
  (playlistId?: string, page?: number, options?: SongsRequestOptions): Promise<Song[]>;
}

const ENDPOINT = 'playlists';

const get: getSongsFunction = async (playlistId, page, options) => {
  const response: AxiosResponse<songsHttpResponse> = await apiClient.get(`${ENDPOINT}/${playlistId}/songs`, {
    params: { limit: dataGetValue(options, 'limit', 50), page: page ?? 1 },
    signal: options?.signal,
  });

  return response.data.data;
};

export default {
  get,
};
