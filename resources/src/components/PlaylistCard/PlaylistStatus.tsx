import { useTranslation } from 'react-i18next';
import CheckCircleIcon from '@mui/icons-material/CheckCircle';
import NotInterestedIcon from '@mui/icons-material/NotInterested';
import { Tooltip, useTheme } from '@mui/material';
import { grey } from '@mui/material/colors';

export interface PlaylistStatusProps {
  active: boolean;
}

const PlaylistStatus = ({ active }: PlaylistStatusProps) => {
  const { t } = useTranslation();

  const iconStyle = { color: grey[500], fontSize: 20 };

  return active ? (
    <Tooltip title={t('playlist.status.active')}>
      <CheckCircleIcon sx={iconStyle} />
    </Tooltip>
  ) : (
    <Tooltip title={t('playlist.status.inactive')}>
      <NotInterestedIcon sx={iconStyle} />
    </Tooltip>
  );
};

export default PlaylistStatus;
