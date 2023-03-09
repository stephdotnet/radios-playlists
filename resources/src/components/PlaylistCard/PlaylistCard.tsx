import { Box, Chip, Typography, useTheme } from '@mui/material';
import { Playlist } from '@/types/Playlist';
import PlaylistCardStyles from './PlaylistCard.style';

export interface PlaylistCardProps {
  playlist: Playlist;
}

const PlaylistCard = ({ playlist }: PlaylistCardProps) => {
  const theme = useTheme();
  const styles = PlaylistCardStyles(theme);

  return (
    <Box textAlign="center" className="box" sx={styles.playlistCard.box}>
      <Typography variant="h4">{playlist.slug}</Typography>
      <Box>
        <Chip label={playlist.songs_count} sx={styles.playlistCard.box.chip} />
      </Box>
    </Box>
  );
};

export default PlaylistCard;
