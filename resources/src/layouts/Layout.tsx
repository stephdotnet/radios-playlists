import { Outlet } from 'react-router-dom';
import Alerts from './Alerts';
import Header from './Header';

const Layout = () => {
  return (
    <>
      <Alerts />
      <Header />
      <Outlet />
    </>
  );
};

export default Layout;
