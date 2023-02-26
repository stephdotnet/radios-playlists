import { Box, Link, useTheme } from '@mui/material';
import { Song } from '@/types/Song';
import SongCardStyles from './SongCard.style';

const SongCard = ({ song }: { song: Song }) => {
  const theme = useTheme();
  const styles = SongCardStyles(theme);

  return (
    <Link href={song.spotify_url} target="_blank" sx={styles.songCard}>
      <Box paddingX={2} paddingY={2} sx={styles.songCard.box}>
        {song.name} ({song.artists})
      </Box>
    </Link>
  );
};

export default SongCard;
