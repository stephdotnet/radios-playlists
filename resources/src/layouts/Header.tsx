import { useTranslation } from 'react-i18next';
import { Container, Typography } from '@mui/material';
import Grid from '@mui/material/Grid';
import SpotifyAuth from '@/components/SpotifyAuth';
import { useGetMe } from '@/hooks/useGetMe';

const Header = () => {
  const { t } = useTranslation();
  const { isLoading: isLoadingMe, error: errorMe, data: dataMe } = useGetMe();

  return (
    <Container>
      <Grid container alignItems="baseline" direction="row" marginY={2}>
        <Grid item xs={12} md={6}>
          <Typography variant="h4" component="h1">
            {t('system.app.title')}
          </Typography>
        </Grid>
        <Grid item xs={12} md={6} display="flex" justifyContent="right" alignItems="baseline">
          <SpotifyAuth dataMe={dataMe} isLoading={isLoadingMe} error={errorMe} textAlign="right" />
        </Grid>
      </Grid>
    </Container>
  );
};

export default Header;
