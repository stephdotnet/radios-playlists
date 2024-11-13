import { Theme } from '@mui/material';

const PlaylistCardStyles = (theme: Theme) => {
  return {
    playlistCard: {
      borderRadius: 2,
      textDecoration: 'none',
      '&:focus-visible': {
        outline: `1px auto ${theme.palette.primary.main}`,
      },
      '&:hover .box, &:focus-visible .box': {
        backgroundColor: theme.palette.action.hover,
      },
      '&:active .box': {
        backgroundColor: theme.palette.action.selected,
      },
      box: {
        marginY: 1,
        borderRadius: 2,
        padding: 1.5,
        display: 'flex',
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'center',
        chip: {
          marginLeft: 1,
          fontSize: '12',
          padding: 1.5,
        },
        sync: {
          marginLeft: 1,
          fontSize: '10',
          padding: 0.5,
          height: '22px',
        },
      },
    },
  };
};

export default PlaylistCardStyles;
