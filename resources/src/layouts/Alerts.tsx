import { Alert } from '@mui/lab';
import { Box } from '@mui/material';
import { Stack } from '@mui/material';
import {
  Alert as AlertContextType,
  useAppContext,
} from '@/utils/context/AppContext';

const Alerts = () => {
  const { alerts, dismissAlert } = useAppContext();

  const handleClose = (alert: AlertContextType) => {
    dismissAlert(alert.id);
  };

  return (
    <Box position="absolute" top="1rem" right="1rem">
      <Stack sx={{ width: '100%' }} spacing={1}>
        {alerts.map((alert) => (
          <Alert
            onClose={() => handleClose(alert)}
            severity={alert.type}
            key={alert.id}
          >
            {alert.title}
          </Alert>
        ))}
      </Stack>
    </Box>
  );
};

export default Alerts;
