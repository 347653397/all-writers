import * as loginService from '../services/login';
import { Toast } from 'antd-mobile';

const delayCode = ms => new Promise(resolve => { setInterval(resolve, ms); });//播放定时器

export default {
  namespace: 'login',
  state: {
    code_img: [],//图形码
    drawLoad: function name(params) {

    },//图形码方法
    loginData: {
      name: '',
      phone: '',
      graph_code: '',
      note_code: '',
    },
    note_time: 60
  },
  reducers: {
    save(state, action) {
      return {
        ...state, ...action.payload
      };
    },
  },
  effects: {
    *codeGet({ payload }, { call, put, select }) {
      const { data } = yield call(loginService.sendSms, payload);
      if (data.status == 200) {
        //获取验证码，发送成功后调用定时60秒
        Toast.info('发送成功', 1)
        yield put({ type: 'codeTime' });
      } else {
        Toast.info(data.msg, 1)
      }
    },
    *codeTime({ payload }, { call, put, select }) {
      const { note_time } = yield select(state => state.login);
      yield put({ type: 'save', payload: { note_time: note_time - 1 } });
      if (note_time != 0) {
        yield call(delayCode, 1000);
        yield put({ type: 'codeTime' });
      } else {
        yield put({ type: 'save', payload: { note_time: 60 } });
      }
    },
    *bindingMobile({ payload }, { call, put, select }) {
      const { drawLoad } = yield select(state => state.login);
      const { data } = yield call(loginService.bindingMobile, payload);
      if (data.status == 200) {
        Toast.info('绑定成功', 1);
        window.history.go(-1);
      } else {
        drawLoad && drawLoad();
        Toast.info(data.msg, 1)
      }
    },
  },
  subscriptions: {
    setup({ dispatch, history }) {
      return history.listen(({ pathname, query }) => {
        if (pathname === '/login') {
          dispatch({ type: 'save', payload: { note_time: 60 } });
        }
      });
    },
  },
};
