import { AxiosResponse } from 'axios';
import { Playlist, playlistHttpResponse, playlistsHttpResponse } from '@/types/Playlist';
import { dataGetValue, env } from '@/utils';
import { apiClient } from './api';

interface PlaylistsRequestOptions {
  page?: number;
  limit?: number;
  signal?: AbortSignal;
}

interface getPlaylistsFunction {
  (page?: number, options?: PlaylistsRequestOptions): Promise<Playlist[]>;
}

interface getPlaylistFunction {
  (id: string, options?: PlaylistsRequestOptions): Promise<Playlist>;
}

const ENDPOINT = 'playlists';

const get: getPlaylistsFunction = async (page, options) => {
  const response: AxiosResponse<playlistsHttpResponse> = await apiClient.get(ENDPOINT, {
    params: { limit: dataGetValue(options, 'limit', 50), page: page ?? 1 },
    signal: options?.signal,
  });

  return response.data.data;
};

const show: getPlaylistFunction = async (id, options) => {
  const response: AxiosResponse<playlistHttpResponse> = await apiClient.get(`${ENDPOINT}/${id}`, {
    params: { limit: dataGetValue(options, 'limit', 50), page: dataGetValue(options, 'page', 1) },
    signal: options?.signal,
  });

  return response.data.data;
};

export default {
  get,
  show,
};
