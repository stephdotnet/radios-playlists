import { useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';
import { Link as RouterLink, useNavigate, useParams } from 'react-router-dom';
import { Favorite } from '@mui/icons-material';
import QueueMusicIcon from '@mui/icons-material/QueueMusic';
import { Box, Container, Grid, Pagination, Skeleton } from '@mui/material';
import HttpErrorBox from '@/components/HttpErrorBox';
import { PlaylistCard } from '@/components/PlaylistCard';
import { SpotifyAuthButton } from '@/components/SpotifyAuth';
import { SpotifyButton } from '@/components/SpotifyButton';
import Link from '@/components/atoms/Link';
import PlaylistsSkeleton from '@/components/skeletons/PlaylistsSkeleton';
import SongsSkeleton from '@/components/skeletons/SongsSkeleton';
import { useGetMe } from '@/hooks/useGetMe';
import { useShowPlaylist } from '@/hooks/useGetPlaylists';
import { useGetSongs } from '@/hooks/useGetSongs';
import { useRemoveSong } from '@/hooks/useRemoveSong';
import { pages } from '@/hooks/useRouter';
import useSyncPlaylist from '@/hooks/useSyncPlaylist';
import { Song } from '@/types/Song';
import { useAppContext } from '@/utils/context/AppContext';
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

  const {
    isInitialLoading: isLoadingPlaylist,
    error: errorPlaylist,
    data: dataPlaylist,
  } = useShowPlaylist(id);

  const {
    isInitialLoading: isLoadingSongs,
    error: errorSongs,
    data: dataSongs,
  } = useGetSongs(id, page);

  const handleSyncPlaylist = async () => {
    await mutate(id, {
      onError: () => {
        addAlert({
          type: 'error',
          title: t('pages.playlist_detail.sync.error'),
        });
      },
    });
  };

  const handlePageChange = (
    event: React.ChangeEvent<unknown>,
    page: number,
  ) => {
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
                  text="Synchoniser la playlist"
                  endIcon={<QueueMusicIcon />}
                  onClick={handleSyncPlaylist}
                  loading={IsLoadingPlaylistSync}
                  loadingPosition="end"
                />
              )
            )
          ) : isLoadingMe ? (
            <Box>
              <Skeleton variant="rectangular" height={35} width={200} />
            </Box>
          ) : (
            <Box display="flex" flexDirection="column" alignItems="center">
              <Box marginBottom={1}>
                {t('pages.playlist_detail.login_info')}
              </Box>
              <Box>
                <SpotifyAuthButton />
              </Box>
            </Box>
          )}
        </Box>
        <Box display="flex" justifyContent="center" marginBottom={4}>
          {dataPlaylist?.url && dataPlaylist.url !== null ? (
            <SpotifyButton
              text="Voir sur spotify"
              endIcon={<Favorite />}
              loading={isLoadingPlaylist}
              onClick={() => {
                /* @ts-ignore */
                window.open(dataPlaylist.url, '_blank');
              }}
            />
          ) : (
            <></>
          )}
        </Box>

        {isLoadingSongs || !dataSongs ? (
          <SongsSkeleton />
        ) : errorSongs ? (
          <HttpErrorBox error={errorSongs} />
        ) : (
          <Box marginTop={2} position="relative">
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
                boundaryCount={2}
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
