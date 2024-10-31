import { useTranslation } from 'react-i18next';
import { generatePath } from 'react-router-dom';
import { SpotifyButton } from '@/components/SpotifyButton';
import { pages } from '@hooks/useRouter';

const SpotifyAuthButton = () => {
  const { t } = useTranslation();
  const redirectUrl = generatePath(pages.home.path);

  return (
    <SpotifyButton
      text={t('auth.login.button_text')}
      href={`/spotify-redirect?redirect=${redirectUrl}`}
    />
  );
};

export default SpotifyAuthButton;
