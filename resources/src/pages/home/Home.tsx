import { useTranslation } from 'react-i18next';
import { Box, Container, Grid, Typography, useTheme } from '@mui/material';
import HttpErrorBox from '@/components/HttpErrorBox';
import { PlaylistCardLink } from '@/components/PlaylistCard';
import SpotifyAuth from '@/components/SpotifyAuth';
import PlaylistsSkeleton from '@/components/skeletons/PlaylistsSkeleton';
import { useGetMe } from '@/hooks/useGetMe';
import { useGetPlaylists } from '@/hooks/useGetPlaylists';

const Home = () => {
  const { t } = useTranslation();
  const { isLoading, error, data } = useGetPlaylists();
  const { isLoading: isLoadingMe, error: errorMe, data: dataMe } = useGetMe();
  const theme = useTheme();

  return (
    <Container>
      <Box paddingY={2}>
        <Box>
          {isLoading ? (
            <PlaylistsSkeleton />
          ) : error ? (
            <HttpErrorBox error={error} />
          ) : (
            data?.map((playlist) => <PlaylistCardLink playlist={playlist} key={playlist.id} />)
          )}
        </Box>
      </Box>
    </Container>
  );
};

export default Home;
