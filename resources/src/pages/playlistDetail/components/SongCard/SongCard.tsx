import { useTranslation } from 'react-i18next';
import DeleteForeverIcon from '@mui/icons-material/DeleteForever';
import { Box, IconButton, Link, Typography, useTheme } from '@mui/material';
import { Song } from '@/types/Song';
import { formatDate } from '@/utils/system/date';
import SongCardStyles from './SongCard.style';

const SongCard = ({
  song,
  deleteSong,
  showDelete,
}: {
  song: Song;
  deleteSong: (song: Song) => void;
  showDelete?: boolean;
}) => {
  const { t } = useTranslation();
  const theme = useTheme();
  const styles = SongCardStyles(theme);

  const handleDelete = () => {
    deleteSong(song);
  };

  return (
    <Box sx={styles.songCard}>
      <Box
        paddingX={2}
        minHeight={48}
        sx={styles.songCard.box}
        display="flex"
        justifyContent="space-between"
        alignItems="center"
      >
        <Box
          py={1}
          sx={{
            whiteSpace: 'nowrap',
            overflow: 'hidden',
            textOverflow: 'ellipsis',
          }}
        >
          <Link
            href={song.spotify_url}
            target="_blank"
            sx={{
              textDecoration: 'none',
            }}
          >
            {song.name} ({song.artists})
          </Link>
          <Typography
            variant="caption"
            sx={{
              display: 'block',
              color: theme.palette.grey[700],
            }}
          >
            {t('pages.playlist_detail.song.card.added_on', {
              date: formatDate(song.created_at),
            })}
          </Typography>
        </Box>
        {showDelete && (
          <Box>
            <IconButton onClick={handleDelete}>
              <DeleteForeverIcon />
            </IconButton>
          </Box>
        )}
      </Box>
    </Box>
  );
};

export default SongCard;
