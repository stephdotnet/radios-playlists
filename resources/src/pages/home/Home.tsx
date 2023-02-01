import { useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';
import { Box, Button, Container, Typography } from '@mui/material';
import Grid from '@mui/material/Unstable_Grid2';

const Home = () => {
  const { t } = useTranslation();
  const [file, setFile] = useState<File | null>(null);

  const handleDelete = () => {
    setFile(null);
  };

  return (
    <Container>
      <Box marginY={2}>
        <Typography variant="h3" component="h1" textAlign="center">
          {t('pages.home')}
        </Typography>
      </Box>
    </Container>
  );
};

export default Home;
