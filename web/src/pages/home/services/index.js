import { GetJson, PostJson } from '../../../utils/request';

//获取课程列表
export function getCourseList(values) {
  return PostJson('/getCourseList', values);
}

//获取首页分类标签
export function getCategoryList(values) {
  return PostJson('/getCategoryList', values);
}
//获取首页广告位
export function getBannerList(values) {
  return PostJson('/getBannerList', values);
}
