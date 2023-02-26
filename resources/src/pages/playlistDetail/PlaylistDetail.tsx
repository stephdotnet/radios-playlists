import { useTranslation } from 'react-i18next';
import { Link as RouterLink, useNavigate, useParams } from 'react-router-dom';
import QueueMusicIcon from '@mui/icons-material/QueueMusic';
import { Box, Container, Typography } from '@mui/material';
import HttpErrorBox from '@/components/HttpErrorBox';
import { PlaylistCard } from '@/components/PlaylistCard';
import SpotifyButton from '@/components/SpotifyButton';
import Link from '@/components/atoms/Link';
import PlaylistsSkeleton from '@/components/skeletons/PlaylistsSkeleton';
import SongsSkeleton from '@/components/skeletons/SongsSkeleton copy';
import { useShowPlaylist } from '@/hooks/useGetPlaylists';
import { useGetSongs } from '@/hooks/useGetSongs';
import { pages } from '@/hooks/useRouter';

type Props = {
  id: string;
};

const PlaylistDetail: React.FC = () => {
  const { id } = useParams<Props>();
  const { t } = useTranslation();
  const navigate = useNavigate();

  if (!id) {
    navigate('/404');
    return <></>;
  }

  const { isLoading: isLoadingPlaylist, error: errorPlaylist, data: dataPlaylist } = useShowPlaylist(id);
  const { isLoading: isLoadingSongs, error: errorSongs, data: dataSongs } = useGetSongs(id);

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

      <Box display="flex" justifyContent="center">
        <SpotifyButton text="Synchoniser la playlist" endIcon={<QueueMusicIcon />} />
      </Box>

      {isLoadingSongs ? (
        <SongsSkeleton />
      ) : errorSongs ? (
        <HttpErrorBox error={errorSongs} />
      ) : (
        <Box marginTop={2}>
          {dataSongs &&
            dataSongs.map((song) => {
              return (
                <Box key={song.id} marginBottom={1}>
                  <Typography>
                    <Link href={song.spotify_url} target="_blank">
                      {song.name} ({song.artists})
                    </Link>
                  </Typography>
                </Box>
              );
            })}
        </Box>
      )}
    </Container>
  );
};

export default PlaylistDetail;
