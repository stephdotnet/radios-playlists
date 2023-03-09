import BaseLink from '@mui/material/Link';
import { styled } from '@mui/material/styles';

const Link = styled(BaseLink)(() => ({
  textDecoration: 'none',
  '&:hover': {
    textDecoration: 'none',
  },
})) as typeof BaseLink;

export default Link;
