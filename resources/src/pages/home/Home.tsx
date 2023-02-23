import { useTranslation } from 'react-i18next';
import { Box, Container, Grid, Skeleton, Typography, useTheme } from '@mui/material';
import HttpErrorBox from '@/components/HttpErrorBox';
import SpotifyAuth from '@/components/SpotifyAuth';
import { useGetMe } from '@/hooks/useGetMe';
import { useGetPlaylists } from '@/hooks/useGetPlaylists';
import PlaylistCard from './components/PlaylistCard';

const Home = () => {
  const { t } = useTranslation();
  const { isLoading, error, data } = useGetPlaylists();
  const { isLoading: isLoadingMe, error: errorMe, data: dataMe } = useGetMe();
  const theme = useTheme();

  return (
    <Container>
      <Box paddingY={2}>
        <Typography color={theme.palette.text.primary} variant="h3" component="h1" textAlign="center" marginBottom={3}>
          {t('pages.playlists.title')}
        </Typography>
        <Grid container>
          <Grid item xs={12} md={6} marginY={2}>
            <SpotifyAuth dataMe={dataMe} isLoading={isLoadingMe} error={errorMe} />
          </Grid>
        </Grid>
        <Box>
          {isLoading ? (
            <PlaylistsSkeleton />
          ) : error ? (
            <HttpErrorBox error={error} />
          ) : (
            data?.map((playlist) => <PlaylistCard playlist={playlist} key={playlist.id} />)
          )}
        </Box>
      </Box>
    </Container>
  );
};

const PlaylistsSkeleton = () => {
  return (
    <Box display="flex" justifyContent="center" flexDirection="column">
      <Box marginY={1}>
        <Skeleton variant="rectangular" height={50} width="100%" component="div" />
      </Box>
      <Box marginY={1}>
        <Skeleton variant="rectangular" height={50} width="100%" component="div" />
      </Box>
    </Box>
  );
};

export default Home;
