import { GetJson, PostJson } from '../utils/request';

export function query(params) {
  return GetJson('/api/users', params);
}
