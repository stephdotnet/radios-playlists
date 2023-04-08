import { useTranslation } from 'react-i18next';
import { Box } from '@mui/material';
import { Chip } from '@mui/material';
import { Typography } from '@mui/material';
import Link from '@/components/atoms/Link';
import { PlaylistSync } from '@/types/Playlist';

const PlaylistSyncSummary = ({ data }: { data: PlaylistSync }) => {
  const { t } = useTranslation();

  return (
    <Box textAlign="center">
      <Typography>
        {data.spotify_playlist.recently_created
          ? t('pages.playlist_detail.sync.summary.success_and_creation')
          : t('pages.playlist_detail.sync.summary.success')}
      </Typography>
      <Box marginY={2}>
        <Chip
          label={t('pages.playlist_detail.sync.summary.chip.label', {
            count: data.synced_songs,
          })}
        />
      </Box>
      <Link target="_blank" href={data.spotify_playlist.url}>
        {t('pages.playlist_detail.sync.playlist.spotify_url')}
      </Link>
    </Box>
  );
};

export default PlaylistSyncSummary;
