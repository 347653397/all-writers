import * as releaseService from '../services/release';
import { Toast,Modal } from 'antd-mobile';
import { routerRedux } from 'dva/router';

const alert=Modal.alert;
const delayCodeStatus = ms => new Promise(resolve => { setInterval(resolve, ms); });//播放定时器

export default {
  namespace: 'release',
  state: {
    dispatch:'',
    ReaderURL:'',
    ReaderVisible:false,
    localData:'',

    title:'',
    author:'',
    audio_pic:'',
    audio_brief:'',
    is_original:2,
    price:'',
    content:'',

    audioData:{},
    inType:'',
    item_id:'',
    releaseRouter:'/users/submission'
  },
  reducers: {
    save(state, action) {
      return {
        ...state, ...action.payload
      };
    },
  },
  effects: {
    
    /*调取图片*/
    *chooseImage({ payload }, { call, put, select }) {
        const { dispatch } = yield select(state => state.release)
        wx.ready(() => {
            wx.chooseImage({
                "count": 1, // 默认9
                "sizeType": ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
                "sourceType": ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
                "success": (res) => {
                    console.log(res)
                   if (res.errMsg == "chooseImage:ok") {
                        dispatch({
                            type: 'getLocalImgData',
                            payload: {
                                localIds: res.localIds[0],
                                ...payload
                            }
                        })
                        dispatch({
                            type: 'save',
                            payload: {
                                localIds: res.localIds[0],
                            }
                        })
                    } else {
                        Toast.info('选择失败', 1);
                    }
                    // var localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
                }
            });
        })
    },
    /*获取本地图片，src-base64展示*/
    *getLocalImgData({ payload }, { call, put, select }) {
        const { dispatch } = yield select(state => state.release)
        wx.ready(() => {
            wx.getLocalImgData({
                localId: payload.localIds, // 图片的localID
                success: (res) => {
                    if (res.errMsg == "getLocalImgData:ok") {
                        dispatch({
                            type: 'save',
                            payload: {
                                ReaderURL: res.localData,
                                ReaderVisible:true
                                // uploadPrview: false,
                                // uploadType: payload.uploadType
                            }
                        })
                    }
                    // var localData = res.localData; // localData是图片的base64数据，可以用img标签显示
                }
            });
        })
    },
    //投稿
    *pushArticle({ payload ,routerPush}, { call, put, select }) {
      console.log(payload)
      const { inType,releaseRouter,dispatch } = yield select(state => state.release)
      const { data } = yield call(releaseService.pushArticle, {...payload});
      if (data.status == 200) {
         inType?alert('修改成功，等待审核'):alert('投稿成功，等待审核');
         yield call(delayCodeStatus, 1000);
         dispatch(routerRedux.push({pathname:releaseRouter}))
         // inType?window.history.go(-1):routerPush && routerPush()
         // yield put(routerRedux({pathname:releaseRouter}))
         releaseRouter
      } else {
        Toast.info(data.msg, 1)
      }
    },
    *getAudioInfo({ payload ,routerPush}, { call, put, select }) {
      const { data } = yield call(releaseService.getAudioInfo, payload);
      if (data.status == 200) {
        const {title,author,audio_brief,content,is_original,price,audio_pic,item_id}=data.data.audio;
         yield put({
            type:'save',
            payload:{
                audioData:data.data.audio,
                title,
                author,
                audio_brief,
                content,
                is_original,
                price,
                item_id,
                localData:audio_pic,
                inType:payload.in_type
            }
         })
      } else {
        Toast.info(data.msg, 1)
      }
    },
    
  },
  subscriptions: {
    setup({ dispatch, history }) {
        dispatch({ type: 'save', payload: { dispatch } })
      return history.listen(({ pathname, query }) => {
        if (pathname === '/release') {
          query.id && dispatch({ type: 'getAudioInfo', payload: { item_id: query.id,in_type:2 } });
        }
      });
    },
  },
};
