import { useRouteError } from 'react-router-dom';
import { Box } from '@mui/material';
import { Container } from '@mui/material';
import { Typography } from '@mui/material';
import { useRouteErrorType } from '@/types';

function ErrorPage() {
  const error = useRouteError() as useRouteErrorType;

  return (
    <>
      <Container>
        <Box>
          <Typography variant="h1" textAlign="center">
            {error.status}
          </Typography>
          <Typography variant="h3" textAlign="center">
            {error.statusText}
          </Typography>
        </Box>
      </Container>
    </>
  );
}

export default ErrorPage;
