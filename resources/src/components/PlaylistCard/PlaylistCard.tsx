import { Box, Chip, Typography, useTheme } from '@mui/material';
import { Playlist } from '@/types/Playlist';
import PlaylistStatus from '@components/PlaylistCard/PlaylistStatus';
import PlaylistSyncCount from '@components/PlaylistCard/PlaylistSyncCount';
import PlaylistCardStyles from './PlaylistCard.style';

export interface PlaylistCardProps {
  playlist: Playlist;
}

const PlaylistCard = ({ playlist }: PlaylistCardProps) => {
  const theme = useTheme();
  const styles = PlaylistCardStyles(theme);

  return (
    <Box textAlign="center" className="box" sx={styles.playlistCard.box}>
      <Typography variant="h4">{playlist.name || playlist.slug}</Typography>

      <Box>
        <Chip label={playlist.songs_count} sx={styles.playlistCard.box.chip} />
      </Box>
      <Box ml={1}>
        <PlaylistStatus active={playlist.active} />
      </Box>
      <PlaylistSyncCount playlist={playlist} />
    </Box>
  );
};

export default PlaylistCard;
