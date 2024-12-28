import { Box, Skeleton } from '@mui/material';
import { Grid } from '@mui/material';

interface SongsSkeletonProps {
  count?: number;
}

const SongSkeleton = () => (
  <Skeleton variant="rounded" height={40} width="100%" component="div" />
);

const PaginationSkeleton = () => (
  <Skeleton
    variant="rounded"
    height={30}
    sx={{
      margin: 1,
    }}
    width="50%"
    component="div"
  />
);

const SongsSkeleton = ({ count = 10 }: SongsSkeletonProps) => {
  return (
    <>
      <Box
        display="flex"
        justifyContent="center"
        alignItems="center"
        flexDirection="column"
      >
        <PaginationSkeleton />
        {Array.from(Array(count)).map((_, index) => (
          <Grid container columnSpacing={2} key={index}>
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
        <PaginationSkeleton />
      </Box>
    </>
  );
};

export default SongsSkeleton;
