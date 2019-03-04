import { GetJson, PostJson } from '../../../utils/request';


//投稿
export function pushArticle(values) {
  return PostJson('/pushArticle', values);
}

//获取编辑投稿详情
export function getAudioInfo(values) {
  return PostJson('/getAudioInfo', values);
}

