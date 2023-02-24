import { useTranslation } from 'react-i18next';
import ExitToApp from '@mui/icons-material/ExitToApp';
import { Box, Button, Link, Skeleton, Typography, useTheme } from '@mui/material';
import { AxiosError } from 'axios';
import { Me } from '@/types/Me';
import SpotifyAuthButtonStyle from './SpotifyAuth.style';

const SpotifyAuth = ({ dataMe, isLoading, error }: { dataMe?: Me; isLoading: boolean; error: AxiosError | null }) => {
  const { t } = useTranslation();
  const authStyle = SpotifyAuthButtonStyle(useTheme());
  const theme = useTheme();

  const AuthComponent = () => {
    if (!dataMe?.display_name) {
      return (
        <Button sx={authStyle.authButton} component={Link} href={'/spotify-redirect'}>
          {t('auth.login.button_text')}
        </Button>
      );
    }
    return (
      <Typography>
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
        <Skeleton variant="rectangular" height={30} width={200} />
      ) : error ? (
        <ErrorAuth />
      ) : (
        <AuthComponent />
      )}
    </Box>
  );
};

export default SpotifyAuth;
