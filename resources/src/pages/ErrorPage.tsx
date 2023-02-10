import { useRouteError } from 'react-router-dom';
import Box from '@mui/material/Box';
import Container from '@mui/material/Container';
import Typography from '@mui/material/Typography';
import Header from '@/layouts/Header';
import { useRouteErrorType } from '@/types';

function ErrorPage() {
  const error = useRouteError() as useRouteErrorType;

  return (
    <>
      <Header />
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
