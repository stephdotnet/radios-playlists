import { useMutation } from '@tanstack/react-query';
import { AxiosError } from 'axios';
import { PlaylistSync } from '@/types/Playlist';
import playlists from '@/utils/api/playlists';

const useSyncPlaylist = () => {
  return useMutation<PlaylistSync, AxiosError, string, () => void>({
    mutationFn: (id: string) => {
      return playlists.sync(id);
    },
  });
};

export default useSyncPlaylist;
