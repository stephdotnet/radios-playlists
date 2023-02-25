import { LoadingButton } from '@mui/lab';
import { Link, useTheme } from '@mui/material';
import SpotifyButtonStyle from './SpotifyButton.style';

interface SpotifyButtonProps {
  text: string;
  href?: string;
  endIcon?: React.ReactNode;
}

const SpotifyButton = ({ text, ...props }: SpotifyButtonProps) => {
  const authStyle = SpotifyButtonStyle(useTheme());

  return (
    <LoadingButton sx={authStyle.authButton} component={Link} {...props}>
      {text}
    </LoadingButton>
  );
};

export default SpotifyButton;
