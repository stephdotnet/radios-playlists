import { LoadingButton, LoadingButtonProps } from '@mui/lab';
import { Link, useTheme } from '@mui/material';
import SpotifyButtonStyle from './SpotifyButton.style';

type SpotifyButtonProps = LoadingButtonProps & {
  text: string;
  href?: string;
  endIcon?: React.ReactNode;
  component?: React.ElementType;
};

const SpotifyButton = ({ text, ...props }: SpotifyButtonProps) => {
  const authStyle = SpotifyButtonStyle(useTheme());

  return (
    <LoadingButton sx={authStyle.authButton} component={Link} {...props}>
      {text}
    </LoadingButton>
  );
};

export default SpotifyButton;
