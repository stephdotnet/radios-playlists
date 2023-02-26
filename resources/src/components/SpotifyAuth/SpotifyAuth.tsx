import { useEffect } from 'react';
import { useTranslation } from 'react-i18next';
import { useLocation } from 'react-router-dom';
import ExitToApp from '@mui/icons-material/ExitToApp';
import { Box, Link, Skeleton, Typography, useTheme } from '@mui/material';
import { AxiosError } from 'axios';
import SpotifyButton from '@/components/SpotifyButton/SpotifyButton';
import { Me } from '@/types/Me';

interface SpotifyAuthProps {
  dataMe?: Me;
  isLoading: boolean;
  error: AxiosError | null;
  textAlign?: 'left' | 'right' | 'center';
}

const SpotifyAuth = ({ dataMe, isLoading, error, textAlign }: SpotifyAuthProps) => {
  const { t } = useTranslation();
  const theme = useTheme();
  const location = useLocation();

  useEffect(() => {
    console.log(location);
  }, [location]);

  const AuthComponent = () => {
    if (!dataMe?.display_name) {
      return (
        <SpotifyButton text={t('auth.login.button_text')} href={`/spotify-redirect?redirect=${location.pathname}`} />
      );
    }
    return (
      <Typography textAlign={textAlign}>
        {t('auth.greeting', { name: dataMe?.display_name })}
        <Link href="/logout" display="inline-flex" alignItems="center" ml={0.5}>
          {t('auth.logout')} <ExitToApp sx={{ marginLeft: 0.5 }} />
        </Link>
      </Typography>
    );
  };

  const ErrorAuth = () => {
    return <Typography color={theme.palette.error.dark}>{t('auth.error')}</Typography>;
  };

  return (
    <Box>
      {isLoading ? (
        <Box>
          <Skeleton variant="rectangular" height={35} width={200} />
        </Box>
      ) : error ? (
        <ErrorAuth />
      ) : (
        <AuthComponent />
      )}
    </Box>
  );
};

export default SpotifyAuth;
