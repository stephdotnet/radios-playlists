import { I18nextProvider } from 'react-i18next';
import { RouterProvider } from 'react-router-dom';
import useRouter from '@hooks/useRouter';
import i18n from '@utils/localisation/i18n';
import '@css/app.scss';
import '@fontsource/roboto/300.css';
import '@fontsource/roboto/400.css';
import '@fontsource/roboto/500.css';
import '@fontsource/roboto/700.css';

export default function App() {
  const { router } = useRouter();

  return (
    <I18nextProvider i18n={i18n}>
      <RouterProvider router={router} />
    </I18nextProvider>
  );
}
