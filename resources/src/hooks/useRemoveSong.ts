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
      term,
    }: {
      playlistId: string;
      songId: number;
      currentPage: number;
      term: string | null;
    }) => {
      await queryClient.cancelQueries({
        queryKey: getQueryKeyList(playlistId, currentPage, term),
      });

      const previousSongs = queryClient.getQueryData<getSongsResponse>(
        getQueryKeyList(playlistId, currentPage, term),
      );

      if (previousSongs) {
        queryClient.setQueryData<getSongsResponse>(
          getQueryKeyList(playlistId, currentPage, term),
          (old) =>
            ({
              ...old,
              songs: old?.songs.filter((song) => song.id !== songId),
            } as getSongsResponse),
        );

        if (currentPage != previousSongs.meta.last_page) {
          const nextPageSongs = queryClient.getQueryData<getSongsResponse>(
            getQueryKeyList(playlistId, currentPage + 1, term),
          );

          if (nextPageSongs) {
            const songToPush: Song = nextPageSongs.songs.shift() as Song;

            queryClient.setQueryData<getSongsResponse>(
              getQueryKeyList(playlistId, currentPage, term),
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
                getQueryKeyList(playlistId, currentPage + 1, term),
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
      const { playlistId, currentPage, term } = variables;
      queryClient.invalidateQueries(
        getQueryKeyList(playlistId, currentPage, term),
      );
    },
  });
}
