import { GetJson, PostJson } from '../../../utils/request';


//获取课程详情
export function getCourseInfo(values) {
  return PostJson('/getCourseInfo', values);
}


//获取音频详情
export function getAudioInfo(values) {
  return PostJson('/getAudioInfo', values);
}

//保存播放数量
export function savePlayNum(values) {
  return PostJson('/savePlayNum', values);
}

//获取音频播放地址
export function getCourseAddr(values) {
  return PostJson('/getCourseAddr', values);
}
//音频购买
export function buyAudio(values) {
  return PostJson('/buyAudio', values);
}
//获取内容评分项
// export function getContentItems(values) {
//   return PostJson('/getContentItems', values);
// }
//发表评论及打分
export function comment(values) {
  return PostJson('/comment', values);
}
//评论点赞及取消
export function likeComment(values) {
  return PostJson('/ ', values);
}

//对评论或课程打赏
export function reward(values) {
  return PostJson('/reward', values);
}

//获取评论列表
export function audioComment(values) {
  return PostJson('/audioComment', values);
}

//获取微信配置
export function weixinShareConfig(values) {
  return PostJson('/weixinShareConfig', values);
}
//参与竞拍
export function takeAuction(values) {
  return PostJson(`/takeAuction`, values);
}