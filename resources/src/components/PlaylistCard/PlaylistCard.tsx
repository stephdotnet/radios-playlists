import { Box, Chip, Tooltip, Typography, useTheme } from '@mui/material';
import { Playlist } from '@/types/Playlist';
import PlaylistStatus from '@components/PlaylistCard/PlaylistStatus';
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
      {playlist.active && (
        <Box>
          <Tooltip title={`${playlist.songs_to_sync} songs to sync`}>
            <Chip
              label={playlist.songs_to_sync}
              sx={styles.playlistCard.box.sync}
            />
          </Tooltip>
        </Box>
      )}
    </Box>
  );
};

export default PlaylistCard;
