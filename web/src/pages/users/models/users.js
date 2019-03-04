import * as usersService from '../services/users';
import { Toast,Modal } from 'antd-mobile';
import { routerRedux } from 'dva/router';
const alert=Modal.alert;
export default {
  namespace: 'users',
  state: {
    phone: '',
    cash_balance: '',
    userData: [],
    orderData: [],
    orderSel: '',
    toReward: [],
    moenyPageNum: 1,
    moenyPageSize: 12,
    cashBalance:'',
    toRewardTotal: 0,
    moenyLength: true,
    //提现
    forwardStatus: false,
    refreshDetai: '',
    //我的评论
    commentPageNum: 1,
    commentPageSize: 12,
    commentData: [],
    commentLength: true,
    refreshComment: '',
    //帮助中心
    helpData: [{
      title: '所获得打赏怎么提现？',
      content: '提现的流程为：1.进入个人中心，点击提现按钮； 2.若未绑定银行账号则先填写绑定银行账号； 3.填写提现金额，点击确认提交审核； 4.通过审核打款到指定银行账户',
      display: false,
    }, {
      title: '所获得打赏怎么提现？',
      content: '提现的流程为：1.进入个人中心，点击提现按钮； 2.若未绑定银行账号则先填写绑定银行账号； 3.填写提现金额，点击确认提交审核； 4.通过审核打款到指定银行账户',
      display: false,
    }, {
      title: '所获得打赏怎么提现？',
      content: '提现的流程为：1.进入个人中心，点击提现按钮； 2.若未绑定银行账号则先填写绑定银行账号； 3.填写提现金额，点击确认提交审核； 4.通过审核打款到指定银行账户',
      display: false,
    }, {
      title: '所获得打赏怎么提现？',
      content: '提现的流程为：1.进入个人中心，点击提现按钮； 2.若未绑定银行账号则先填写绑定银行账号； 3.填写提现金额，点击确认提交审核； 4.通过审核打款到指定银行账户',
      display: false,
    }, {
      title: '所获得打赏怎么提现？',
      content: '提现的流程为：1.进入个人中心，点击提现按钮； 2.若未绑定银行账号则先填写绑定银行账号； 3.填写提现金额，点击确认提交审核； 4.通过审核打款到指定银行账户',
      display: false,
    }],
    //建议
    commentProposal: '',

    //我的投稿
    subPageNum: 1,
    subPageSize: 12,
    subData: [],
    subotal:0,
    subLength: true,
    refreshLoading:'',
    myCourseType:1,//投稿/竞拍
  },
  reducers: {
    save(state, action) {
      return {
        ...state, ...action.payload
      };
    },
  },
  effects: {
    *userCenter({ payload }, { call, put, select }) {
      const { data } = yield call(usersService.userCenter, payload);
      if (data.status == 200) {
        yield put({ type: 'save', payload: { userData: data.data } })
      }
    },
    *applyWithdraw({ payload }, { call, put, select }) {
      const { data } = yield call(usersService.applyWithdraw, payload);
      if (data.status == 200) {
        yield put({ type: 'save', payload: { forwardStatus: true } })
        // Toast.info('提交成功',1)

      } else {
        Toast.info(data.msg, 1)
      }
    },
    *myOrderlist({ payload }, { call, put, select }) {
      const { data } = yield call(usersService.myOrderlist, payload);
      if (data.status == 200) {
        yield put({ type: 'save', payload: { orderData: data.data } })
      }
    },
    *myWallet({ payload }, { call, put, select }) {
      const { moenyPageNum, moenyPageSize, toReward, refreshDetai } = yield select(state => state.users);
      const { data } = yield call(usersService.myWallet, { page: moenyPageNum, pageSize: moenyPageSize });
      if (data.status == 200) {
        toReward.push(...data.data.list)
        yield put({ type: 'save', payload: { toReward: toReward,cashBalance:data.data.cash_balance, toRewardTotal: data.data.total, moenyLength: data.data.list.length < moenyPageSize ? false : true, moenyPageNum: moenyPageNum + 1 } })
        refreshDetai && refreshDetai()
      }
    },
    *myComment({ payload }, { call, put, select }) {
      const { commentPageNum, refreshComment, commentPageSize, commentData } = yield select(state => state.users);
      const { data } = yield call(usersService.myComment, { page: commentPageNum, pageSize: commentPageSize });
      if (data.status == 200) {
        commentData.push(...data.data.list);
        yield put({ type: 'save', payload: { pullUpStatus: 0, commentData: commentData, Commentotal: data.data.total, commentLength: data.data.list.length < commentPageSize ? false : true, commentPageNum: commentPageNum + 1 } })
        refreshComment && refreshComment()
      }
    },

    *deleteFailOrder({ payload }, { call, put, select }) {
      const { data } = yield call(usersService.deleteFailOrder, payload);
      if (data.status == 200) {
        Toast.info('删除成功', 1);
        yield put({ type: 'myOrderlist' })
      } else {
        Toast.info(data.msg, 1)
      }
    },
    *submitFeedback({ payload }, { call, put, select }) {
      const { data } = yield call(usersService.submitFeedback, payload);
      if (data.status == 200) {
        //  yield put({type:'save',payload:{userData:data.data}})
        Toast.info('提交成功', 1)
        window.history.go(-1);
      } else {
        Toast.info(data.msg, 1)
      }
    },
    *myCourse({ payload }, { call, put, select }) {
      const { subPageNum, refreshLoading, subPageSize, subData,myCourseType } = yield select(state => state.users);
      const { data } = yield call(usersService.myCourse, { page: subPageNum, pageSize: subPageSize,type:myCourseType });
      if (data.status == 200) {
        subData.push(...data.data.courses);
        yield put({ type: 'save', payload: {  subData: subData, subotal: data.data.count, subLength: data.data.courses.length < subPageSize ? false : true, subPageNum: subPageNum + 1 } })
        refreshLoading && refreshLoading()
      }
    },
    *applyAuction({ payload }, { call, put, select }) {
      const { subData} = yield select(state => state.users);
      const { data } = yield call(usersService.applyAuction, payload);
      if (data.status == 200) {
        alert('操作提示', '申请已提交，请等待工作人员联系您', [
          { text: '确定', onPress: () => {console.log('确定')}},
        ])
        subData[payload.index].auction_status=2;
        yield put({type:'save',payload:{subData}})
      } else {
        Toast.info(data.msg, 1)
      }
    },
    *delCourse({ payload }, { call, put, select }) {
      const { subData} = yield select(state => state.users);
      const { data } = yield call(usersService.delCourse, payload);
      if (data.status == 200) {
         Toast.info('删除成功', 1);
          yield put({type:'save',payload:{subData:[],subPageNum:1,subLength:true}})
         yield put({type:'myCourse'})
      } else {
        Toast.info(data.msg, 1)
      }
    },
    *usersRouter({ payload }, { call, put }) {
      yield put(routerRedux.push(payload.router))
    }
  },
  subscriptions: {
    setup({ dispatch, history }) {
      return history.listen(({ pathname, query }) => {
        switch (pathname) {
          case '/':
            document.title = '人人编剧'
            break;
          case '/login':
            document.title = '手机号绑定'
            break;
          case '/users':
            document.title = '我的主页';
            dispatch({ type: 'userCenter' })
            break;
          case '/users/detai':
            document.title = '我的钱包';
            dispatch({ type: 'save', payload: { toReward: [], moenyPageNum: 1, moenyLength: true, refreshDetai: '' } })
            break;
          case '/users/comment':
            document.title = '我的评论';
            dispatch({ type: 'save', payload: { commentPageNum: 1, commentData: [], commentLength: true, refreshComment: '' } })
            break;
          case '/users/forward':
            document.title = '提现申请';
            dispatch({ type: 'userCenter' })
            dispatch({ type: 'save', payload: { cash_balance: '', forwardStatus: false } })
            break;
          case '/users/order':
            document.title = '我的订单';
            dispatch({ type: 'save', payload: { commentVisible: false } })
            dispatch({ type: 'myOrderlist' })
            break;
          case '/users/submission':
            document.title = '我的投稿';
            dispatch({ type: 'save',payload:{myCourseType:1} });

            break;
            case '/users/auction':
            document.title = '我的拍卖';
             dispatch({ type: 'save',payload:{myCourseType:2} });
            break;
          case '/users/proposal':
            document.title = '意见建议'
            break;
          case '/users/help':
            document.title = '帮助中心'
            break;
          default:
            break;
        }

      });
    },
  },
};
