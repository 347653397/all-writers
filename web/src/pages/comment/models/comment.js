import * as commentService from '../services/comment';
import { Toast,Modal } from 'antd-mobile';
import { getDateDiff } from '../../../utils'

const alert=Modal.alert;
const delayCode = ms => new Promise(resolve => { setInterval(resolve, ms); });//播放定时器

export default {
  namespace: 'comment',
  state: {
    dispatch:'',
   commentList:'',
   commentLength:true,
   commentId:'',

   commentReply:[],
   oneComment:[],
   commentTotal:0,
   commentPageSize:12,
   commentPageNum:1,

   commentLike:[],

   commentReward:[],
   refreshLoading:'',

   commentListPlay:false,//当前评论回复打赏启用状态
  },
  reducers: {
    save(state, action) {
      return {
        ...state, ...action.payload
      };
    },
  },
  effects: {
    
    //获取评论回复列表
    *getCommentReplyList({ payload ,commentUpdate}, { call, put, select }) {
       const { commentPageNum,commentId, commentPageSize, commentReply, refreshLoading } = yield select(state => state.comment);
	      const { data } = yield call(commentService.getCommentReplyList, { comment_id:commentId, page: commentPageNum, pageSize: commentPageSize });
	      if (data.status == 200) {
	      	//评论日期转换
	        let commentList = [];
	        data.data.comment_reply.map((d, i) => {
	          commentList.push({
	            ...d,
	            created_at: getDateDiff(d.created_at * 1000)
	          })
	        })
	        commentReply.push(...commentList)
	        yield put({ type: 'save', payload: { commentLoading:false,commentReply: commentUpdate?commentList:commentReply,oneComment:data.data.one_comment, commentTotal: data.data.total, commentLength: data.data.comment_reply.length < commentPageSize ? false : true, commentPageNum: commentPageNum + 1 } })
	        refreshLoading && refreshLoading()
	      }
    },
    //
    *commentLikeList({ payload ,routerPush}, { call, put, select }) {
       const { commentPageNum, commentPageSize, commentId,commentLike, refreshLoading } = yield select(state => state.comment);
	      const { data } = yield call(commentService.commentLikeList, { comment_id:commentId, page: commentPageNum, pageSize: commentPageSize });
	      if (data.status == 200) {
	      	commentLike.push(...data.data.list)
	        // commentReply.push(...data.data.comment_reply)
	        yield put({ type: 'save', payload: { commentLike: commentLike,commentTotal: data.data.total, commentLength: data.data.list.length < commentPageSize ? false : true, commentPageNum: commentPageNum + 1 } })
	        refreshLoading && refreshLoading()
	      }
    },
    *commentRewardList({ payload ,routerPush}, { call, put, select }) {
       const { commentPageNum, commentPageSize, commentReward,commentId, refreshLoading } = yield select(state => state.comment);
	      const { data } = yield call(commentService.commentRewardList, {comment_id:commentId, page: commentPageNum, pageSize: commentPageSize });
	      if (data.status == 200) {
	      	commentReward.push(...data.data.list)
	        // commentReply.push(...data.data.comment_reply)
	        yield put({ type: 'save', payload: { commentReward: commentReward,commentTotal: data.data.total, commentLength: data.data.list.length < commentPageSize ? false : true, commentPageNum: commentPageNum + 1 } })
	        refreshLoading && refreshLoading()
	      }
    },
     //评论点赞及取消
    *likeComment({ payload }, { call, put, select }) {
      const { oneComment } = yield select(state => state.comment);
      const { data } = yield call(commentService.likeComment, payload);
      if (data.status == 200) {
        oneComment.is_star = payload.type;
        if(Number(oneComment.comment_reply)!=0){
          oneComment.comment_reply = payload.type==1?Number(oneComment.comment_reply)+1:Number(oneComment.comment_reply)-1;
        }
        yield put({ type: 'save', payload: { oneComment } })
        yield put({type:'getCommentReplyList'})

      } else {
        Toast.info(data.msg, 1)
      }
    },
    *commentReply({ payload, getLoading, commentUpdate }, { call, put, select }) {
      const { commentId } = yield select(state => state.comment);
      const { data } = yield call(commentService.commentReply, payload);
      if (data.status == 200) {
        yield put({ type: 'detai/save', payload: { commentVisible: false, commentText: '' } })
        Toast.info('回复成功', 1);
        if (commentUpdate) {
          yield put({ type: 'getCommentReplyList', commentUpdate })
        } else {
          getLoading && getLoading();
        }
      } else {
        Toast.info(data.msg, 1)
      }
    },
    
  },
  subscriptions: {
    setup({ dispatch, history }) {
        dispatch({ type: 'save', payload: { dispatch } })
         return history.listen(({ pathname, query }) => {
        // if (pathname === '/login') {
        //   dispatch({ type: 'save', payload: { note_time: 60 } });
        // }
      });
    },
  },
};
