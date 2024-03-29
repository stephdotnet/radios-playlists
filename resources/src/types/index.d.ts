import 'vite/client';

declare module '*.module.scss' {
  const classes: { [key: string]: string };
  export default classes;
}

declare module '@mui/material/styles' {
  interface ThemeOptions {
    spotify: {
      text: string;
      primary: SimplePaletteColorOptions;
      borderRadius: string;
    };
  }
}

export type CustomTheme = Theme & ThemeOptions;

declare module '@mui/material/Button' {
  interface ButtonPropsVariantOverrides {
    spotify: true;
  }
}

declare module 'i18next' {
  interface CustomTypeOptions {
    returnNull: false;
  }
}

export interface useRouteErrorType {
  status: number;
  statusText: string;
  data: string;
  error: {
    message: string;
    stack: string;
  };
  internal: boolean;
}
