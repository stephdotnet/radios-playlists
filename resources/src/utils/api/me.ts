import { AxiosResponse } from 'axios';
import { Me, MeHttpResponse } from '@/types/Me';
import { apiClient } from './api';

interface MeRequestOptions {
  signal?: AbortSignal;
}

interface getMeFunction {
  (options?: MeRequestOptions): Promise<Me>;
}

const ENDPOINT = 'me';

const get: getMeFunction = async (options) => {
  const response: AxiosResponse<MeHttpResponse> = await apiClient.get(ENDPOINT, {
    signal: options?.signal,
  });

  return response.data.data;
};

export default {
  get,
};
