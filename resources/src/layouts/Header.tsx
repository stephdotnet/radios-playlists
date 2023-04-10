import { useEffect } from 'react';
import { useTranslation } from 'react-i18next';
import {
  Box,
  Container,
  Typography,
  useMediaQuery,
  useTheme,
} from '@mui/material';
import { Grid } from '@mui/material';
import { SpotifyAuth } from '@/components/SpotifyAuth';
import { useGetMe } from '@/hooks/useGetMe';
import { useAppContext } from '@/utils/context/AppContext';

const Header = () => {
  const { t } = useTranslation();
  const { isLoading: isLoadingMe, error: errorMe, data: dataMe } = useGetMe();
  const { setUser } = useAppContext();

  const theme = useTheme();
  const isSmallScreen = useMediaQuery(theme.breakpoints.down('sm'));

  useEffect(() => {
    if (!isLoadingMe) {
      setUser(dataMe || null);
    }
  }, [dataMe, isLoadingMe]);

  return (
    <Container>
      <Grid container alignItems="end" direction="row" marginY={2}>
        <Grid item xs={12} sm={6}>
          <Typography variant="h4" component="h1">
            {t('system.app.title')}
          </Typography>
        </Grid>
        <Grid
          item
          xs={12}
          sm={6}
          sx={{
            display: 'flex',
            justifyContent: isSmallScreen ? 'center' : 'right',
            marginTop: isSmallScreen ? 2 : 0,
          }}
        >
          <Box>
            <SpotifyAuth
              dataMe={dataMe}
              isLoading={isLoadingMe}
              error={errorMe}
              textAlign="right"
            />
          </Box>
        </Grid>
      </Grid>
    </Container>
  );
};

export default Header;
