export interface Me {
  display_name?: string;
  is_admin?: boolean;
}
export interface MeHttpResponse {
  data: Me;
}
