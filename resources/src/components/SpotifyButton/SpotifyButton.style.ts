import { CustomTheme } from '@/types';

const SpotifyButtonStyle = (theme: CustomTheme) => {
  return {
    authButton: {
      px: 3,
      borderRadius: theme.spotify.borderRadius,
      color: theme.spotify.text,
      backgroundColor: theme.spotify.primary.main,
      '&:focus-visible': {
        outline: `1px auto ${theme.palette.primary.main}`,
      },
      '&:hover, &:focus-visible': {
        backgroundColor: theme.spotify.primary.light,
      },
      '&:active': {
        backgroundColor: theme.spotify.primary.dark,
      },
    },
  };
};

export default SpotifyButtonStyle;
