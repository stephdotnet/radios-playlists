import React from 'react';
import { useTranslation } from 'react-i18next';
import { Box, Button, Modal, Typography, useTheme } from '@mui/material';
import { Song } from '@/types/Song';
import DeleteModalStyles from './DeleteModal.style';

const DeleteModal = ({
  isOpen,
  setIsOpen,
  song,
  deleteSong,
}: {
  isOpen: boolean;
  setIsOpen: React.Dispatch<React.SetStateAction<boolean>>;
  song: Song | null;
  deleteSong: (song: Song | null) => void;
}) => {
  const theme = useTheme();
  const styles = DeleteModalStyles(theme);
  const { t } = useTranslation();
  const handleClose = () => {
    setIsOpen(false);
  };

  const handleDelete = () => {
    deleteSong(song);
  };

  return (
    <Modal
      open={isOpen}
      onClose={handleClose}
      aria-labelledby="modal-modal-title"
      aria-describedby="modal-modal-description"
    >
      <Box sx={styles.DeleteModal}>
        <Typography id="modal-modal-title" variant="h6" component="h2">
          {t('pages.playlist_detail.songs.remove_song_title')}
        </Typography>
        <Box marginY={1} textAlign="center">
          <Typography variant="body2">
            {song?.name} ({song?.artists})
          </Typography>
        </Box>
        <Box display="flex" justifyContent="center" marginTop={4}>
          <Button color="error" onClick={handleDelete} variant="outlined">
            {t('system.actions.delete')}
          </Button>
        </Box>
      </Box>
    </Modal>
  );
};

export default DeleteModal;
