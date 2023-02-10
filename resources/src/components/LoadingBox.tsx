import { Box, CircularProgress } from '@mui/material';

const LoadingBox = () => {
  return (
    <Box justifyContent="center" display="flex" alignItems="center" height="300">
      <CircularProgress />
    </Box>
  );
};

export default LoadingBox;
