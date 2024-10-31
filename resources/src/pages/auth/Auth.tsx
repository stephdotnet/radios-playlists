import { useTranslation } from 'react-i18next';
import { Box } from '@mui/material';
import { Container } from '@mui/material';
import { SpotifyAuthButton } from '@components/SpotifyAuth';

function AuthPage() {
  const { t } = useTranslation();
  return (
    <>
      <Container>
        <Box>
          <Box display="flex" flexDirection="column" alignItems="center">
            <Box marginBottom={1}>{t('pages.playlist_detail.login_info')}</Box>
            <Box>
              <SpotifyAuthButton />
            </Box>
          </Box>
        </Box>
      </Container>
    </>
  );
}

export default AuthPage;
