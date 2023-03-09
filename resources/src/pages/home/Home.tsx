import { Box, Container } from '@mui/material';
import HttpErrorBox from '@/components/HttpErrorBox';
import { PlaylistCardLink } from '@/components/PlaylistCard';
import PlaylistsSkeleton from '@/components/skeletons/PlaylistsSkeleton';
import { useGetPlaylists } from '@/hooks/useGetPlaylists';

const Home = () => {
  const { isLoading, error, data } = useGetPlaylists();

  return (
    <Container>
      <Box>
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
