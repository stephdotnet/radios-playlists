import DeleteForeverIcon from '@mui/icons-material/DeleteForever';
import { Box, IconButton, Link, useTheme } from '@mui/material';
import { Song } from '@/types/Song';
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
