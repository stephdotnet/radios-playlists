import { AxiosResponse } from 'axios';
import {
  Playlist,
  PlaylistHttpResponse,
  PlaylistStats,
  PlaylistStatsHttpResponse,
  PlaylistSync,
  PlaylistSyncCount,
  PlaylistSyncCountHttpResponse,
  PlaylistSyncHttpResponse,
  PlaylistsHttpResponse,
} from '@/types/Playlist';
import { dataGetValue } from '@/utils';
import { apiClient } from './api';

/*
|--------------------------------------------------------------------------
| Request Options
|--------------------------------------------------------------------------
*/

interface PlaylistsRequestOptions {
  page?: number;
  limit?: number;
  signal?: AbortSignal;
}

/*
|--------------------------------------------------------------------------
| List
|--------------------------------------------------------------------------
*/

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

/*
|--------------------------------------------------------------------------
| Show
|--------------------------------------------------------------------------
*/

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

/*
|--------------------------------------------------------------------------
| Sync
|--------------------------------------------------------------------------
*/

interface syncPlaylistFunction {
  (id: string): Promise<PlaylistSync>;
}

const sync: syncPlaylistFunction = async (id) => {
  const response: AxiosResponse<PlaylistSyncHttpResponse> =
    await apiClient.post(`${ENDPOINT}/${id}/sync`, {});

  return response.data.data;
};

/*
|--------------------------------------------------------------------------
| Stats
|--------------------------------------------------------------------------
*/

interface GetPlaylistStatsFunction {
  (id: number, options?: PlaylistsRequestOptions): Promise<PlaylistStats>;
}

const stats: GetPlaylistStatsFunction = async (id, options) => {
  const response: AxiosResponse<PlaylistStatsHttpResponse> =
    await apiClient.get(`${ENDPOINT}/${id}/stats`, { signal: options?.signal });

  return response.data;
};

/*
|--------------------------------------------------------------------------
| Stats
|--------------------------------------------------------------------------
*/

interface GetPlaylistSyncCountFunction {
  (id: number, options?: PlaylistsRequestOptions): Promise<PlaylistSyncCount>;
}

const syncCount: GetPlaylistSyncCountFunction = async (id, options) => {
  const response: AxiosResponse<PlaylistSyncCountHttpResponse> =
    await apiClient.get(`${ENDPOINT}/${id}/sync-count`, {
      signal: options?.signal,
    });

  return response.data.data;
};

/*
|--------------------------------------------------------------------------
| Export
|--------------------------------------------------------------------------
*/

export default {
  get,
  show,
  sync,
  syncCount,
  stats,
};
