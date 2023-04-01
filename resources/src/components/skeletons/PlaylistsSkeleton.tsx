import { Box, Skeleton } from '@mui/material';

interface PlaylistsSkeletongProps {
  count?: number;
}

const PlaylistsSkeleton = ({ count = 2 }: PlaylistsSkeletongProps) => {
  return (
    <Box display="flex" justifyContent="center" flexDirection="column">
      {Array.from(Array(count)).map((_, index) => (
        <Box key={index} marginY={1}>
          <Skeleton
            variant="rectangular"
            height={50}
            width="100%"
            component="div"
          />
        </Box>
      ))}
    </Box>
  );
};

export default PlaylistsSkeleton;
