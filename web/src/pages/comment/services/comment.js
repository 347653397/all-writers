import { GetJson, PostJson } from '../../../utils/request';

//获取评论回复列表
export function getCommentReplyList(values) {
  return PostJson('/getCommentReplyList', values);
}
//评论回复
export function commentReply(values) {
  return PostJson('/commentReply', values);
}
//评论打赏列表
export function commentRewardList(values) {
  return PostJson('/commentRewardList', values);
}
//评论点赞列表
export function commentLikeList(values) {
  return PostJson('/commentLikeList', values);
}
//评论点赞及取消
export function likeComment(values) {
  return PostJson('/likeComment', values);
}
