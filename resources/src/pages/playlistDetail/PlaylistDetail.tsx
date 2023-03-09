import { useTranslation } from 'react-i18next';
import { Link as RouterLink, useNavigate, useParams } from 'react-router-dom';
import QueueMusicIcon from '@mui/icons-material/QueueMusic';
import { Box, Container, Grid } from '@mui/material';
import HttpErrorBox from '@/components/HttpErrorBox';
import { PlaylistCard } from '@/components/PlaylistCard';
import { SpotifyAuthButton } from '@/components/SpotifyAuth';
import { SpotifyButton } from '@/components/SpotifyButton';
import Link from '@/components/atoms/Link';
import PlaylistsSkeleton from '@/components/skeletons/PlaylistsSkeleton';
import SongsSkeleton from '@/components/skeletons/SongsSkeleton';
import { useShowPlaylist } from '@/hooks/useGetPlaylists';
import { useGetSongs } from '@/hooks/useGetSongs';
import { pages } from '@/hooks/useRouter';
import useSyncPlaylist from '@/hooks/useSyncPlaylist';
import { useAppContext } from '@/utils/context/AppContext';
import PlaylistSyncSummary from './PlaylistSyncSummary';
import SongCard from './SongCard';

type Props = {
  id: string;
};

const PlaylistDetail: React.FC = () => {
  const navigate = useNavigate();
  const { id } = useParams<Props>();
  const { t } = useTranslation();
  const { user, addAlert } = useAppContext();
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
    isLoading: isLoadingPlaylist,
    error: errorPlaylist,
    data: dataPlaylist,
  } = useShowPlaylist(id);

  const {
    isLoading: isLoadingSongs,
    error: errorSongs,
    data: dataSongs,
  } = useGetSongs(id);

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

  return (
    <Container>
      <Box>
        <Link to={pages.home.path} component={RouterLink}>
          {t('pages.playlist.go_back_to_playlists')}
        </Link>
      </Box>

      {isLoadingPlaylist ? (
        <PlaylistsSkeleton count={1} />
      ) : errorPlaylist ? (
        <HttpErrorBox error={errorPlaylist} />
      ) : (
        <PlaylistCard playlist={dataPlaylist} />
      )}

      <Box
        display="flex"
        justifyContent="center"
        marginBottom={4}
        marginTop={2}
      >
        {user ? (
          PlaylistSyncData ? (
            <PlaylistSyncSummary data={PlaylistSyncData} />
          ) : (
            <SpotifyButton
              text="Synchoniser la playlist"
              endIcon={<QueueMusicIcon />}
              onClick={handleSyncPlaylist}
              loading={IsLoadingPlaylistSync}
              loadingPosition="end"
            />
          )
        ) : (
          <Box display="flex" flexDirection="column" alignItems="center">
            <Box marginBottom={1}>{t('pages.playlist_detail.login_info')}</Box>
            <Box>
              <SpotifyAuthButton />
            </Box>
          </Box>
        )}
      </Box>

      {isLoadingSongs ? (
        <SongsSkeleton />
      ) : errorSongs ? (
        <HttpErrorBox error={errorSongs} />
      ) : (
        <Box marginTop={2}>
          <Grid container columnSpacing={2} rowSpacing={1}>
            {dataSongs &&
              dataSongs.map((song) => {
                return (
                  <Grid item key={song.id} xs={12} md={6}>
                    <SongCard song={song} />
                  </Grid>
                );
              })}
          </Grid>
        </Box>
      )}
    </Container>
  );
};

export default PlaylistDetail;
