import { Box, Skeleton } from '@mui/material';

interface SongsSkeletonProps {
  count?: number;
}

const SongsSkeleton = ({ count = 5 }: SongsSkeletonProps): JSX.Element => {
  return (
    <Box display="flex" justifyContent="left" flexDirection="column">
      {Array.from(Array(count)).map((_, index) => (
        <Box key={index} marginY={1}>
          <Skeleton variant="rectangular" height={20} width="50%" component="div" />
        </Box>
      ))}
    </Box>
  );
};

export default SongsSkeleton;
