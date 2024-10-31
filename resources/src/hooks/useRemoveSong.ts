import { useMutation, useQueryClient } from '@tanstack/react-query';
import { Song } from '@/types/Song';
import songs, { GetSongsResponse } from '@/utils/api/songs';
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

      const previousSongs = queryClient.getQueryData<GetSongsResponse>(
        getQueryKeyList(playlistId, currentPage, term),
      );

      if (previousSongs) {
        queryClient.setQueryData<GetSongsResponse>(
          getQueryKeyList(playlistId, currentPage, term),
          (old) =>
            ({
              ...old,
              songs: old?.songs.filter((song) => song.id !== songId),
            } as GetSongsResponse),
        );

        if (currentPage != previousSongs.meta.last_page) {
          const nextPageSongs = queryClient.getQueryData<GetSongsResponse>(
            getQueryKeyList(playlistId, currentPage + 1, term),
          );

          if (nextPageSongs) {
            const songToPush: Song = nextPageSongs.songs.shift() as Song;

            queryClient.setQueryData<GetSongsResponse>(
              getQueryKeyList(playlistId, currentPage, term),
              (old) => {
                const newSongs = [...(old?.songs ?? []), songToPush];

                return {
                  ...old,
                  songs: newSongs,
                } as GetSongsResponse;
              },
            );

            if (nextPageSongs) {
              queryClient.setQueryData<GetSongsResponse>(
                getQueryKeyList(playlistId, currentPage + 1, term),
                (old) => {
                  return {
                    ...old,
                    songs: nextPageSongs.songs,
                  } as GetSongsResponse;
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
