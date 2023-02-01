import { NavLink, useLocation } from 'react-router-dom';
import { Box, Container, List, ListItemButton, ListItemText } from '@mui/material';
import useRouter, { headerNavItem } from '@hooks/useRouter';

const Header = () => {
  const { headerNav } = useRouter();

  return (
    <Box className="navbar">
      <Container>
        <List sx={{ display: 'flex' }} component="nav">
          {headerNav.map((item, index) => (
            <HeaderNavItem key={index} item={item} />
          ))}
        </List>
      </Container>
    </Box>
  );
};

const HeaderNavItem = ({ item }: { item: headerNavItem }) => {
  const { isActive } = useRouter();
  const { pathname } = useLocation();

  return (
    <ListItemButton
      to={item.path}
      component={NavLink}
      selected={isActive(pathname, item.path)}
      sx={{
        flexGrow: 0,
      }}
    >
      <ListItemText primary={item.label} />
    </ListItemButton>
  );
};

export default Header;
