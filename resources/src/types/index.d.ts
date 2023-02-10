import 'vite/client';

declare module '*.module.scss' {
  const classes: { [key: string]: string };
  export default classes;
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
