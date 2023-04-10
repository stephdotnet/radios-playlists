import { useTranslation } from 'react-i18next';
import ExitToApp from '@mui/icons-material/ExitToApp';
import { Box, Link, Skeleton, Typography, useTheme } from '@mui/material';
import { AxiosError } from 'axios';
import { Me } from '@/types/Me';
import SpotifyAuthButton from './SpotifyAuthButton';

interface SpotifyAuthProps {
  dataMe?: Me;
  isLoading: boolean;
  error: AxiosError | null;
  textAlign?: 'left' | 'right' | 'center';
}

const SpotifyAuth = ({
  dataMe,
  isLoading,
  error,
  textAlign,
}: SpotifyAuthProps) => {
  const { t } = useTranslation();
  const theme = useTheme();

  const AuthComponent = () => {
    if (!dataMe?.display_name) {
      return <SpotifyAuthButton />;
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
    return (
      <Typography color={theme.palette.error.dark}>
        {t('auth.error')}
      </Typography>
    );
  };

  return (
    <Box>
      {isLoading ? (
        <Skeleton variant="rectangular" height={35} width={200} />
      ) : error ? (
        <ErrorAuth />
      ) : (
        <AuthComponent />
      )}
    </Box>
  );
};

export default SpotifyAuth;
