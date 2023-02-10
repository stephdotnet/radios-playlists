import { useTranslation } from 'react-i18next';
import { Box, Container, Typography } from '@mui/material';
import { AxiosError } from 'axios';
import HttpErrorBox from '@/components/HttpErrorBox';
import LoadingBox from '@/components/LoadingBox';
import { useGetPlaylists } from '@/hooks/useGetPlaylists';
import PlaylistCard from './components/PlaylistCard';

const Home = () => {
  const { t } = useTranslation();
  const { isLoading, error, data, isFetching } = useGetPlaylists();

  return (
    <Container>
      <Box paddingY={2}>
        <Typography variant="h3" component="h1" textAlign="center" marginBottom={3}>
          {t('pages.playlists.title')}
        </Typography>
        <Box>
          {isLoading ? (
            <LoadingBox />
          ) : error ? (
            <HttpErrorBox error={error} />
          ) : (
            data?.map((playlist) => {
              return <PlaylistCard playlist={playlist} key={playlist.id} />;
            })
          )}
        </Box>
      </Box>
    </Container>
  );
};

export default Home;
