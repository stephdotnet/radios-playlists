import { Skeleton } from '@mui/material';

const PlaylistsStatsSkeleton = () => {
  return (
    <Skeleton
      variant="rectangular"
      height={200}
      width="100%"
      component="div"
    />
  );
};

export default PlaylistsStatsSkeleton;
