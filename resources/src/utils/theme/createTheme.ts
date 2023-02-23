import { createTheme as CreateMaterialTheme } from '@mui/material/styles';

const createTheme = () => {
  return CreateMaterialTheme({
    palette: {
      mode: 'dark',
    },
    spotify: {
      text: 'rgb(232, 230, 227)',
      primary: {
        light: 'rgb(24, 172, 77)',
        main: 'rgb(23, 148, 67)',
        dark: 'rgb(21, 130, 59)',
      },
      borderRadius: '500px',
    },
  });
};

export default createTheme;
