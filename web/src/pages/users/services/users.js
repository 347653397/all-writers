import { GetJson, PostJson } from '../../../utils/request';

//基本信息
export function userCenter(values) {
  return PostJson(`/userCenter`, values);
}


//用户申请提现
export function applyWithdraw(values) {
  return PostJson(`/applyWithdraw`, values);
}
//用户提交建议反馈
export function submitFeedback(values) {
  return PostJson(`/submitFeedback`, values);
}
//订单列表
export function myOrderlist(values) {
  return PostJson(`/myOrderlist`, values);
}
//未支付删除
export function deleteFailOrder(values) {
  return PostJson(`/deleteFailOrder`, values);
}
//打赏明细
export function myWallet(values) {
  return PostJson(`/myWallet`, values);
}
//我的评论
export function myComment(values) {
  return PostJson(`/myComment`, values);
}
//我的投稿
export function myCourse(values) {
  return PostJson(`/myCourse`, values);
}
//申请竞拍
export function applyAuction(values) {
  return PostJson(`/applyAuction`, values);
}
//删除投稿
export function delCourse(values) {
  return PostJson(`/delCourse`, values);
}
