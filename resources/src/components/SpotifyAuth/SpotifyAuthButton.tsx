import { useTranslation } from 'react-i18next';
import { useLocation } from 'react-router-dom';
import { SpotifyButton } from '@/components/SpotifyButton';

const SpotifyAuthButton = () => {
  const { t } = useTranslation();
  const location = useLocation();

  return <SpotifyButton text={t('auth.login.button_text')} href={`/spotify-redirect?redirect=${location.pathname}`} />;
};

export default SpotifyAuthButton;
