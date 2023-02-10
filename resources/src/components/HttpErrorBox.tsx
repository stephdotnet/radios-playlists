import { Box, Typography } from '@mui/material';
import { AxiosError } from 'axios';

const HttpErrorBox = ({ error }: { error: AxiosError }) => {
  return (
    <Box height="300" display="flex" flexDirection="column" alignItems="center" justifyContent="center">
      <Typography variant="h4" component="p">
        An error occured ({error.code})
      </Typography>
      <Typography variant="body1" component="p">
        {error.message}
      </Typography>
    </Box>
  );
};

export default HttpErrorBox;
