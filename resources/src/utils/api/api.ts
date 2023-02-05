import Axios, { AxiosRequestConfig } from 'axios';
import { env } from '@/utils';

const axios = Axios.create({
  baseURL: env('VITE_APP_API_URL'),
});

export const apiClient = {
  get: (route: string, config: AxiosRequestConfig) =>
    axios.get(route, { signal: config?.signal, params: config?.params }),
  post: (route: string, data: any, config: AxiosRequestConfig) => axios.post(route, data, { signal: config?.signal }),
  put: (route: string, data: any, config: AxiosRequestConfig) => axios.put(route, data, { signal: config?.signal }),
  patch: (route: string, data: any, config: AxiosRequestConfig) => axios.patch(route, data, { signal: config?.signal }),
};
