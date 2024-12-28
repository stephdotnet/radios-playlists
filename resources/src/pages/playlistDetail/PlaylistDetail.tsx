import React, { useRef, useState } from 'react';
import { useTranslation } from 'react-i18next';
import { Link as RouterLink, useNavigate, useParams } from 'react-router-dom';
import { Clear, Favorite } from '@mui/icons-material';
import QueueMusicIcon from '@mui/icons-material/QueueMusic';
import {
  Box,
  Container,
  Grid,
  IconButton,
  InputAdornment,
  Pagination,
  Skeleton,
  TextField,
  useMediaQuery,
  useTheme,
} from '@mui/material';
import { useQueryClient } from '@tanstack/react-query';
import HttpErrorBox from '@/components/HttpErrorBox';
import { PlaylistCard } from '@/components/PlaylistCard';
import { SpotifyButton } from '@/components/SpotifyButton';
import Link from '@/components/atoms/Link';
import PlaylistsSkeleton from '@/components/skeletons/PlaylistsSkeleton';
import SongsSkeleton from '@/components/skeletons/SongsSkeleton';
import { useGetMe } from '@/hooks/useGetMe';
import { getQueryKeySyncCount, useShowPlaylist } from '@/hooks/useGetPlaylists';
import { useGetSongs } from '@/hooks/useGetSongs';
import { useRemoveSong } from '@/hooks/useRemoveSong';
import { pages } from '@/hooks/useRouter';
import useSyncPlaylist from '@/hooks/useSyncPlaylist';
import useDebouncedState from '@/hooks/utils/useDebouncedState';
import { Song } from '@/types/Song';
import { useAppContext } from '@/utils/context/AppContext';
import PlaylistStats from '@components/PlaylistStats/PlaylistStats';
import DeleteModal from './components/DeleteModal/DeleteModal';
import PlaylistSyncSummary from './components/PlaylistSyncSummary';
import SongCard from './components/SongCard';

type Props = {
  id: string;
};

const PlaylistDetail: React.FC = () => {
  const navigate = useNavigate();
  const { id } = useParams<Props>();
  const { t } = useTranslation();
  const { addAlert } = useAppContext();
  const { isLoading: isLoadingMe, data: dataMe } = useGetMe();
  const [page, setPage] = useState(1);
  const [isOpen, setIsOpen] = useState(false);
  const [songToDelete, setSongToDelete] = useState<Song | null>(null);
  const [term, setTerm] = useDebouncedState<string | null>(null, 500);
  const queryClient = useQueryClient();
  const theme = useTheme();
  const isSmall = useMediaQuery(theme.breakpoints.down('sm'));

  const handleConfirmDeleteSong = (song: Song | null) => {
    setIsOpen(true);
    setSongToDelete(song);
  };

  const { mutate: removeSong } = useRemoveSong();

  const handleDeleteSong = (song: Song | null) => {
    setIsOpen(false);
    if (song?.id && id) {
      removeSong({
        songId: song.id,
        playlistId: id,
        currentPage: page,
        term: term,
      });
    }
  };

  const {
    mutate,
    data: PlaylistSyncData,
    isLoading: IsLoadingPlaylistSync,
  } = useSyncPlaylist();

  if (!id) {
    navigate('/404');
    return <></>;
  }

  const inputRef = useRef<HTMLInputElement>(null);
  const handleTitleSearch = (
    event: React.ChangeEvent<HTMLTextAreaElement | HTMLInputElement>,
  ): void => {
    setTerm(event.target.value);
    setPage(1);
  };

  const {
    isInitialLoading: isLoadingPlaylist,
    error: errorPlaylist,
    data: dataPlaylist,
  } = useShowPlaylist(id);

  const {
    isInitialLoading: isLoadingSongs,
    error: errorSongs,
    data: dataSongs,
  } = useGetSongs(id, page, term);

  const handleSyncPlaylist = () => {
    mutate(id, {
      onError: () => {
        addAlert({
          type: 'error',
          title: t('pages.playlist_detail.sync.error'),
        });
      },
      onSettled: () => {
        queryClient.invalidateQueries(getQueryKeySyncCount(id));
      },
    });
  };

  const handlePageChange = (_: React.ChangeEvent<unknown>, page: number) => {
    setPage(page);
  };

  return (
    <>
      <DeleteModal
        isOpen={isOpen}
        setIsOpen={setIsOpen}
        deleteSong={handleDeleteSong}
        song={songToDelete}
      />
      <Container>
        <Box>
          <Link to={pages.home.path} component={RouterLink}>
            {t('pages.playlist.go_back_to_playlists')}
          </Link>
        </Box>

        {isLoadingPlaylist || !dataPlaylist ? (
          <PlaylistsSkeleton count={1} />
        ) : errorPlaylist ? (
          <HttpErrorBox error={errorPlaylist} />
        ) : (
          <PlaylistCard playlist={dataPlaylist} />
        )}

        <Box
          display="flex"
          justifyContent="center"
          marginBottom={1}
          marginTop={2}
        >
          {dataMe ? (
            PlaylistSyncData ? (
              <PlaylistSyncSummary data={PlaylistSyncData} />
            ) : (
              dataMe.is_admin && (
                <SpotifyButton
                  text={t('pages.playlist_detail.playlist.sync_button')}
                  endIcon={<QueueMusicIcon />}
                  onClick={handleSyncPlaylist}
                  loading={IsLoadingPlaylistSync}
                  loadingPosition="end"
                />
              )
            )
          ) : isLoadingMe ? (
            <Box>
              <Skeleton variant="rounded" height={35} width={200} />
            </Box>
          ) : (
            <></>
          )}
        </Box>
        <Box marginY={2}>
          <PlaylistStats playlistId={Number(id)} />
        </Box>
        <Box display="flex" justifyContent="center" marginBottom={2}>
          {isLoadingPlaylist ? (
            <Skeleton variant="rounded" height={35} width={200} />
          ) : (
            dataPlaylist?.url && (
              <SpotifyButton
                text={t('pages.playlist_detail.playlist.open_in_spotify')}
                endIcon={<Favorite />}
                loading={isLoadingPlaylist}
                onClick={() => {
                  if (dataPlaylist.url) {
                    window.open(dataPlaylist.url, '_blank');
                  }
                }}
              />
            )
          )}
        </Box>

        <Box marginBottom={2}>
          <TextField
            label={t('pages.playlist_detail.search.label')}
            onChange={handleTitleSearch}
            inputRef={inputRef}
            InputProps={{
              endAdornment: term && (
                <InputAdornment position="end">
                  <IconButton
                    onClick={() => {
                      if (inputRef?.current) {
                        inputRef.current.value = '';
                        inputRef.current.blur();
                      }

                      setTerm(null);
                    }}
                  >
                    <Clear />
                  </IconButton>
                </InputAdornment>
              ),
            }}
          />
        </Box>

        {isLoadingSongs || !dataSongs ? (
          <SongsSkeleton />
        ) : errorSongs ? (
          <HttpErrorBox error={errorSongs} />
        ) : (
          <Box marginTop={2} position="relative">
            <Box display="flex" justifyContent="center" my={2}>
              <Pagination
                count={dataSongs.meta.last_page}
                defaultPage={page}
                siblingCount={isSmall ? 0 : 2}
                size={isSmall ? 'small' : 'medium'}
                onChange={handlePageChange}
              />
            </Box>
            <Grid container columnSpacing={2} rowSpacing={1}>
              {dataSongs &&
                dataSongs.songs.map((song) => {
                  return (
                    <Grid item key={song.id} xs={12} md={6}>
                      <SongCard
                        song={song}
                        deleteSong={handleConfirmDeleteSong}
                        showDelete={!!dataMe?.is_admin}
                      />
                    </Grid>
                  );
                })}
            </Grid>
            <Box display="flex" justifyContent="center" my={2}>
              <Pagination
                count={dataSongs.meta.last_page}
                defaultPage={page}
                siblingCount={isSmall ? 0 : 2}
                size={isSmall ? 'small' : 'medium'}
                onChange={handlePageChange}
              />
            </Box>
          </Box>
        )}
      </Container>
    </>
  );
};

export default PlaylistDetail;
