import { useMutation, useQueryClient } from '@tanstack/react-query';
import { Song } from '@/types/Song';
import songs, { getSongsResponse } from '@/utils/api/songs';
import { getQueryKeyList } from './useGetSongs';

export function useRemoveSong() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: ({
      playlistId,
      songId,
    }: {
      playlistId: string;
      songId: number;
    }) => {
      return songs.remove(playlistId, songId);
    },
    onMutate: async ({
      playlistId,
      songId,
      currentPage,
    }: {
      playlistId: string;
      songId: number;
      currentPage: number;
    }) => {
      await queryClient.cancelQueries({
        queryKey: getQueryKeyList(playlistId, currentPage),
      });

      const previousSongs = queryClient.getQueryData<getSongsResponse>(
        getQueryKeyList(playlistId, currentPage),
      );

      if (previousSongs) {
        queryClient.setQueryData<getSongsResponse>(
          getQueryKeyList(playlistId, currentPage),
          (old) =>
            ({
              ...old,
              songs: old?.songs.filter((song) => song.id !== songId),
            } as getSongsResponse),
        );

        if (currentPage != previousSongs.meta.last_page) {
          const nextPageSongs = queryClient.getQueryData<getSongsResponse>(
            getQueryKeyList(playlistId, currentPage + 1),
          );

          if (nextPageSongs) {
            const songToPush: Song = nextPageSongs.songs.shift() as Song;

            queryClient.setQueryData<getSongsResponse>(
              getQueryKeyList(playlistId, currentPage),
              (old) => {
                const newSongs = [...(old?.songs ?? []), songToPush];

                return {
                  ...old,
                  songs: newSongs,
                } as getSongsResponse;
              },
            );

            if (nextPageSongs) {
              queryClient.setQueryData<getSongsResponse>(
                getQueryKeyList(playlistId, currentPage + 1),
                (old) => {
                  return {
                    ...old,
                    songs: nextPageSongs.songs,
                  } as getSongsResponse;
                },
              );
            }
          }
        }
      }
    },
    onSettled: (data, error, variables) => {
      const { playlistId, currentPage } = variables;
      queryClient.invalidateQueries(getQueryKeyList(playlistId, currentPage));
    },
  });
}
