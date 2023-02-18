import { useTranslation } from 'react-i18next';
import ExitToApp from '@mui/icons-material/ExitToApp';
import { Box, Button, Container, Grid, Link, Skeleton, Typography, useTheme } from '@mui/material';
import HttpErrorBox from '@/components/HttpErrorBox';
import LoadingBox from '@/components/LoadingBox';
import { useGetMe } from '@/hooks/useGetMe';
import { useGetPlaylists } from '@/hooks/useGetPlaylists';
import { Me } from '@/types/Me';
import PlaylistCard from './components/PlaylistCard';

const Home = () => {
  const { t } = useTranslation();
  const { isLoading, error, data } = useGetPlaylists();
  const { isLoading: isLoadingMe, error: errorMe, data: dataMe } = useGetMe();
  const theme = useTheme();

  const ErrorAuth = () => {
    return <Typography color={theme.palette.error.dark}>{t('auth.error')}</Typography>;
  };

  return (
    <Container>
      <Box paddingY={2}>
        <Typography color={theme.palette.text.primary} variant="h3" component="h1" textAlign="center" marginBottom={3}>
          {t('pages.playlists.title')}
        </Typography>
        <Grid container>
          <Grid item xs={12} md={6}>
            {isLoadingMe ? (
              <Skeleton variant="rectangular" height={40} />
            ) : errorMe ? (
              <ErrorAuth />
            ) : (
              <SpotifyAuth dataMe={dataMe} />
            )}
          </Grid>
        </Grid>
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

const SpotifyAuth = ({ dataMe }: { dataMe?: Me }) => {
  const { t } = useTranslation();
  return (
    <Box>
      {!dataMe?.display_name ? (
        <Button component={Link} href={'/spotify-redirect'}>
          {t('auth.login.button_text')}
        </Button>
      ) : (
        <Typography>
          {t('auth.greeting', { name: dataMe?.display_name })}
          <Link href="/logout" display="inline-flex" alignItems="center" ml={0.5}>
            {t('auth.logout')} <ExitToApp sx={{ marginLeft: 0.5 }} />
          </Link>
        </Typography>
      )}
    </Box>
  );
};

export default Home;
