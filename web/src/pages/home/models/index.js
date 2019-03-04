import * as homeService from '../services';
import Logo from '../../../assets/logo.png';

export default {
  namespace: 'home',
  state: {
    currentKey: 1,//选项卡
    pageSize: 6,
    pageNum: 1,
    homeItem: [],
    homeLength: true,
    bannerData: [],
    categoryData: [],
    mescrollonLoand: '',
    homeLoading: true,
    refreshLoading: true,
    homeRefresh: ''
  },
  reducers: {
    save(state, action) {
      return {
        ...state, ...action.payload
      };
    },
  },
  effects: {
    *getCourseList({ payload }, { call, put, select }) {
      const { pageSize, pageNum, homeItem, categoryData, homeRefresh } = yield select(state => state.home);
      const { data } = yield call(homeService.getCourseList, {
        page: pageNum,
        pageSize,
        cid: payload.currentKey
      });
      // let dataitem=[1,2,3,4,5,56,6,7,7,7,7,7,7,7,7,7,7,7,7,7]
      if (data.status == 200) {
        homeItem.push(...data.data.courses)
        yield put({ type: 'save', payload: { homeItem: homeItem, refreshLoading: false, homeLoading: false, homeLength: data.data.courses.length < pageSize ? false : true, pageNum: pageNum + 1 } })
        let catName = categoryData.filter(d => d.cid == payload.currentKey);
        yield put({
          type: 'detai/onMenuShare', payload: {
            title: catName.cat_name,
            id: '',
            img: Logo,
            brief: '只讲故事，不讲道理。每个人都是自己人生的编剧',
            link: '/?tab=' + payload.currentKey,
            // link: location.href.split('#')[0] + '/#/?tab=' + payload.currentKey
          }
        })
        homeRefresh && homeRefresh()
      }
    },
    *getBannerList({ payload }, { call, put, select }) {
      const { data } = yield call(homeService.getBannerList, payload);
      if (data.status == 200) {
        yield put({ type: 'save', payload: { bannerData: data.data.banner } })
      }
    },
    *getCategoryList({ payload }, { call, put, select }) {
      if (payload.currentKey) {
        yield put({ type: 'save', payload: { currentKey: Number(payload.currentKey) } });
      }
      const { data } = yield call(homeService.getCategoryList, payload);

      if (data.status == 200) {
        let cid = !payload.currentKey ? Number(data.data.category[0].cid) : Number(payload.currentKey);
        yield put({ type: 'save', payload: { categoryData: data.data.category, currentKey: cid } });
        yield put({ type: 'getCourseList', payload: { currentKey: cid } })
      }
    },
  },
  subscriptions: {
    setup({ dispatch, history }) {
      return history.listen(({ pathname, query }) => {
        window.scrollTo(0, 0);
        if (pathname == '/') {
          dispatch({ type: 'save', payload: { pageNum: 1, homeLength: true, homeItem: [], homeLoading: true, refreshLoading: true } });
          dispatch({ type: 'getCategoryList', payload: { currentKey: query.tab } })
        }
      });
    },
  },
};
