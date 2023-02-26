import { Theme } from '@mui/material';

const SongCardStyles = (theme: Theme) => {
  return {
    songCard: {
      display: 'inline-block',
      width: '100%',
      borderRadius: 2,
      borderColor: theme.palette.grey[800],
      borderWidth: '1px',
      borderStyle: 'solid',
      textDecoration: 'none',
      '&:focus-visible': {
        outline: `1px auto ${theme.palette.primary.main}`,
      },
      '&:hover, &:focus-visible': {
        backgroundColor: theme.palette.action.hover,
      },
      '&:active': {
        backgroundColor: theme.palette.action.selected,
      },
      box: {
        textOverflow: 'ellipsis',
        overflow: 'hidden',
        whiteSpace: 'nowrap',
      },
    },
  };
};

export default SongCardStyles;
