import { useTranslation } from 'react-i18next';
import { Link as RouterLink, useNavigate, useParams } from 'react-router-dom';
import { Box, Chip, Container, Link, Typography } from '@mui/material';
import HttpErrorBox from '@/components/HttpErrorBox';
import LoadingBox from '@/components/LoadingBox';
import { useShowPlaylist } from '@/hooks/useGetPlaylists';
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

  const { isLoading, error, data } = useShowPlaylist(id);

  return (
    <Container>
      <Box paddingY={2}>
        <Link to={pages.home.path} component={RouterLink}>
          {t('pages.playlist.go_back_to_playlists')}
        </Link>
      </Box>
      {isLoading ? (
        <LoadingBox />
      ) : error ? (
        <HttpErrorBox error={error} />
      ) : (
        <>
          <Box display="flex" alignItems="center" justifyContent="center" marginY="2">
            <Box>
              <Typography variant="h3" component="h1">
                {data.slug}
              </Typography>
            </Box>
            <Box marginLeft={1}>
              <Chip label={data.songs_count} />
            </Box>
          </Box>
          <Box marginTop={2}>
            {data.songs &&
              data.songs.map((song) => {
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
        </>
      )}
    </Container>
  );
};

export default PlaylistDetail;
