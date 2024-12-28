import { Box, Chip, CircularProgress, Tooltip, useTheme } from '@mui/material';
import { Playlist } from '@/types/Playlist';
import PlaylistCardStyles from '@components/PlaylistCard/PlaylistCard.style';
import { useSyncCountPlaylist } from '@hooks/useGetPlaylists';

export interface PlaylistSyncCountProps {
  playlist: Playlist;
}

const PlaylistSyncCount = ({ playlist }: PlaylistSyncCountProps) => {
  const theme = useTheme();

  const { data, isFetching } = useSyncCountPlaylist(playlist.id.toString());

  const styles = PlaylistCardStyles(theme);

  if (!playlist.active) {
    return null;
  }

  return (
    <Box>
      {isFetching ? (
        <Chip
          label={<CircularProgress size={14} />}
          sx={styles.playlistCard.box.sync}
        />
      ) : (
        <Tooltip title={`${data?.count} songs to sync`}>
          <Chip label={data?.count} sx={styles.playlistCard.box.sync} />
        </Tooltip>
      )}
    </Box>
  );
};

export default PlaylistSyncCount;
