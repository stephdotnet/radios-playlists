import { I18nextProvider } from 'react-i18next';
import { RouterProvider } from 'react-router-dom';
import { StyledEngineProvider } from '@mui/material';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import useRouter from '@hooks/useRouter';
import i18n from '@utils/localisation/i18n';
import '@css/app.scss';
import '@fontsource/roboto/300.css';
import '@fontsource/roboto/400.css';
import '@fontsource/roboto/500.css';
import '@fontsource/roboto/700.css';

const queryClient = new QueryClient();

export default function App() {
  const { router } = useRouter();

  return (
    <I18nextProvider i18n={i18n}>
      <QueryClientProvider client={queryClient}>
        <StyledEngineProvider injectFirst>
          <RouterProvider router={router} />
        </StyledEngineProvider>
      </QueryClientProvider>
    </I18nextProvider>
  );
}
