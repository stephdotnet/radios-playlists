import { Theme } from '@mui/material';

const DeleteModalStyles = (theme: Theme) => {
  return {
    DeleteModal: {
      position: 'absolute' as const,
      top: '50%',
      left: '50%',
      transform: 'translate(-50%, -50%)',
      // width: 400,
      bgcolor: theme.palette.background.paper,
      borderColor: theme.palette.grey[900],
      borderWidth: '1px',
      borderStyle: 'solid',
      boxShadow: 24,
      borderRadius: 5,
      paddingY: 2,
      paddingX: 4,
    },
  };
};

export default DeleteModalStyles;
