import { Box, Skeleton } from '@mui/material';
import { Grid } from '@mui/material';

interface SongsSkeletonProps {
  count?: number;
}

const SongsSkeleton = ({ count = 5 }: SongsSkeletonProps) => {
  const SongSkeleton = () => (
    <Skeleton variant="rectangular" height={40} width="100%" component="div" />
  );

  return (
    <Box display="flex" justifyContent="left" flexDirection="column">
      {Array.from(Array(count)).map((_, index) => (
        <Grid container columnSpacing={2} rowSpacing={1} key={index}>
          <Grid item xs={12} md={6}>
            <Box marginY={1}>
              <SongSkeleton />
            </Box>
          </Grid>
          <Grid item xs={12} md={6}>
            <Box marginY={1}>
              <SongSkeleton />
            </Box>
          </Grid>
        </Grid>
      ))}
    </Box>
  );
};

export default SongsSkeleton;
