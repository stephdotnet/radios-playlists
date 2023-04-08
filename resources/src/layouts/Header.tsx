import { useEffect } from 'react';
import { useTranslation } from 'react-i18next';
import { Container, Typography } from '@mui/material';
import { Grid } from '@mui/material';
import { SpotifyAuth } from '@/components/SpotifyAuth';
import { useGetMe } from '@/hooks/useGetMe';
import { useAppContext } from '@/utils/context/AppContext';

const Header = () => {
  const { t } = useTranslation();
  const { isLoading: isLoadingMe, error: errorMe, data: dataMe } = useGetMe();
  const { setUser } = useAppContext();

  useEffect(() => {
    if (!isLoadingMe) {
      setUser(dataMe || null);
    }
  }, [dataMe, isLoadingMe]);

  return (
    <Container>
      <Grid container alignItems="baseline" direction="row" marginY={2}>
        <Grid item xs={12} md={6}>
          <Typography variant="h4" component="h1">
            {t('system.app.title')}
          </Typography>
        </Grid>
        <Grid
          item
          xs={12}
          md={6}
          display="flex"
          justifyContent="right"
          alignItems="end"
        >
          <SpotifyAuth
            dataMe={dataMe}
            isLoading={isLoadingMe}
            error={errorMe}
            textAlign="right"
          />
        </Grid>
      </Grid>
    </Container>
  );
};

export default Header;
