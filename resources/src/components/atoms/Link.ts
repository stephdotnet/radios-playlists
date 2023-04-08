import { Link as BaseLink } from '@mui/material';
import { styled } from '@mui/material/styles';

const Link = styled(BaseLink)(() => ({
  textDecoration: 'none',
  '&:hover': {
    textDecoration: 'none',
  },
})) as typeof BaseLink;

export default Link;
