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
