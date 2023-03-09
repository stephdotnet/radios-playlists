import { Link as RouterLink, generatePath } from 'react-router-dom';
import { Link, useTheme } from '@mui/material';
import { pages } from '@/hooks/useRouter';
import { Playlist } from '@/types/Playlist';
import PlaylistCard from './PlaylistCard';
import PlaylistCardStyles from './PlaylistCard.style';

export interface PlaylistCardProps {
  playlist: Playlist;
}

const PlaylistCardLink = ({ playlist }: PlaylistCardProps) => {
  const theme = useTheme();
  const page = generatePath(pages.playlistDetail.path, { id: playlist.id.toString() });
  const styles = PlaylistCardStyles(theme);

  return (
    <Link component={RouterLink} to={page} key={playlist.id} sx={styles.playlistCard}>
      <PlaylistCard playlist={playlist} />
    </Link>
  );
};

export default PlaylistCardLink;
