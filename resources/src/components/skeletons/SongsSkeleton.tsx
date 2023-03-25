import { Box, Skeleton } from '@mui/material';
import Grid from '@mui/material/Grid';

interface SongsSkeletonProps {
  count?: number;
}

const SongsSkeleton = ({ count = 5 }: SongsSkeletonProps) => {
  return (
    <Box display="flex" justifyContent="left" flexDirection="column">
      {Array.from(Array(count)).map((_, index) => (
        <Grid container columnSpacing={2} rowSpacing={1}>
          <Grid item key={index} xs={12} md={6}>
            <Box key={index} marginY={1}>
              <Skeleton
                variant="rectangular"
                height={40}
                width="100%"
                component="div"
              />
            </Box>
          </Grid>
          <Grid item key={index} xs={12} md={6}>
            <Box key={index} marginY={1}>
              <Skeleton
                variant="rectangular"
                height={40}
                width="100%"
                component="div"
              />
            </Box>
          </Grid>
        </Grid>
      ))}
    </Box>
  );
};

export default SongsSkeleton;
