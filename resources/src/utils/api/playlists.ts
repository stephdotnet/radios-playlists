import { AxiosResponse } from 'axios';
import {
  Playlist,
  PlaylistHttpResponse,
  PlaylistStats,
  PlaylistStatsHttpResponse,
  PlaylistSync,
  PlaylistSyncHttpResponse,
  PlaylistsHttpResponse,
} from '@/types/Playlist';
import { dataGetValue } from '@/utils';
import { apiClient } from './api';

interface PlaylistsRequestOptions {
  page?: number;
  limit?: number;
  signal?: AbortSignal;
}

interface getPlaylistsFunction {
  (page?: number, options?: PlaylistsRequestOptions): Promise<Playlist[]>;
}

const ENDPOINT = 'playlists';

const get: getPlaylistsFunction = async (page, options) => {
  const response: AxiosResponse<PlaylistsHttpResponse> = await apiClient.get(
    ENDPOINT,
    {
      params: { limit: dataGetValue(options, 'limit', 50), page: page ?? 1 },
      signal: options?.signal,
    },
  );

  return response.data.data;
};

interface getPlaylistFunction {
  (id: string, options?: PlaylistsRequestOptions): Promise<Playlist>;
}

const show: getPlaylistFunction = async (id, options) => {
  const response: AxiosResponse<PlaylistHttpResponse> = await apiClient.get(
    `${ENDPOINT}/${id}`,
    {
      params: {
        limit: dataGetValue(options, 'limit', 50),
        page: dataGetValue(options, 'page', 1),
      },
      signal: options?.signal,
    },
  );

  return response.data.data;
};

interface syncPlaylistFunction {
  (id: string): Promise<PlaylistSync>;
}

const sync: syncPlaylistFunction = async (id) => {
  const response: AxiosResponse<PlaylistSyncHttpResponse> =
    await apiClient.post(`${ENDPOINT}/${id}/sync`, {});

  return response.data.data;
};

interface GetPlaylistStatsFunction {
  (id: number, options?: PlaylistsRequestOptions): Promise<PlaylistStats>;
}

const stats: GetPlaylistStatsFunction = async (id, options) => {
  const response: AxiosResponse<PlaylistStatsHttpResponse> =
    await apiClient.get(`${ENDPOINT}/${id}/stats`, { signal: options?.signal });

  return response.data;
};

export default {
  get,
  show,
  sync,
  stats,
};
