import { GetJson, PostJson } from '../../../utils/request';

// export function remove(id,values) {
//   return GetJson(`/Api/User/bindingMobile`, values);
// }
//获取手机号验证码
export function sendSms(values) {
  return PostJson('/sendSms', values);
}
//用户绑定手机号
export function bindingMobile(values) {
  return PostJson('/bindingMobile', values);
}
