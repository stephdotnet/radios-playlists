import { Link as RouterLink, generatePath } from 'react-router-dom';
import { Box, Chip, Link, Typography, useTheme } from '@mui/material';
import { pages } from '@/hooks/useRouter';
import { Playlist } from '@/types/Playlist';
import PlaylistCardStyles from './PlaylistCard.style';

export interface PlaylistCardProps {
  playlist: Playlist;
}

const PlatlistCard = ({ playlist }: { playlist: Playlist }) => {
  const theme = useTheme();
  const page = generatePath(pages.playlistDetail.path, { id: playlist.id.toString() });
  const styles = PlaylistCardStyles(theme);

  return (
    <Link component={RouterLink} to={page} key={playlist.id} sx={styles.playlistCard}>
      <Box textAlign="center" className="box" sx={styles.playlistCard.box}>
        <Typography variant="h4">{playlist.slug}</Typography>
        <Box>
          <Chip label={playlist.songs_count} sx={styles.playlistCard.box.chip} />
        </Box>
      </Box>
    </Link>
  );
};

export default PlatlistCard;
