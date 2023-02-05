import { createBrowserRouter } from 'react-router-dom';
import ErrorPage from '@/pages/ErrorPage';
import Home from '@/pages/home/Home';
import PlaylistDetail from '@/pages/playlistDetail/PlaylistDetail';
import i18n from '@/utils/localisation/i18n';
import Layout from '@layouts/Layout';

export interface headerNavItem {
  path: string;
  label: string;
}

export const pages = {
  home: {
    path: '/',
    slug: 'home',
  },
  playlistDetail: {
    path: 'playlists/:id',
    slug: 'playlist-detail',
  },
};

const useRouter = () => {
  const isActive = (locationPathname: string, toPathname: string) => {
    return (
      locationPathname === toPathname ||
      (locationPathname.startsWith(toPathname) && locationPathname.charAt(toPathname.length) === '/')
    );
  };

  const router = createBrowserRouter([
    {
      element: <Layout />,
      children: [
        {
          path: pages.home.path,
          element: <Home />,
        },
        {
          path: pages.playlistDetail.path,
          element: <PlaylistDetail />,
        },
      ],
      errorElement: <ErrorPage />,
    },
  ]);

  const headerNav: headerNavItem[] = [
    {
      path: pages.home.path,
      label: i18n.t('pages.home'),
    },
  ];

  return {
    router,
    headerNav,
    isActive,
  };
};

export default useRouter;
