import * as detaiService from '../services';
import { Toast, Modal, Result, Icon } from 'antd-mobile';
import { getDateDiff } from '../../../utils'
const delayDetai = ms => new Promise(resolve => { setInterval(resolve, ms); });//播放定时器
const alert = Modal.alert;

//audio属性参考使用
// onabort	当发生中止事件时运行脚本
// oncanplay	当媒介能够开始播放但可能因缓冲而需要停止时运行脚本  可以开始播放
// oncanplaythrough当媒介能够无需因缓冲而停止即可播放至结尾时运行脚本  无需缓冲可直接播放
// ondurationchange当媒介长度改变时运行脚本
// onemptied	当媒介资源元素突然为空时（网络错误、加载错误等）运行脚本
// onended	当媒介已抵达结尾时运行脚本
// onerror	当在元素加载期间发生错误时运行脚本
// onloadeddata	当加载媒介数据时运行脚本
// onloadedmetadata当媒介元素的持续时间以及其他媒介数据已加载时运行脚本  元数据已加载
// onloadstart	当浏览器开始加载媒介数据时运行脚本  开始加载
// onpause	当媒介数据暂停时运行脚本
// onplay	当媒介数据将要开始播放时运行脚本
// onplaying	当媒介数据已开始播放时运行脚本
// onprogress	当浏览器正在取媒介数据时运行脚本
// onratechange	当媒介数据的播放速率改变时运行脚本
// onreadystatechange	当就绪状态（ready-state）改变时运行脚本
// onseeked	当媒介元素的定位属性 [1] 不再为真且定位已结束时运行脚本
// onseeking	当媒介元素的定位属性为真且定位已开始时运行脚本
// onstalled	当取回媒介数据过程中（延迟）存在错误时运行脚本
// onsuspend	当浏览器已在取媒介数据但在取回整个媒介文件之前停止时运行脚本
// ontimeupdate	当媒介改变其播放位置时运行脚本
// onvolumechange	当媒介改变音量亦或当音量被设置为静音时运行脚本
// onwaiting	当媒介已停止播放但打算继续播放时运行脚本

export default {
  namespace: 'detai',
  state: {
    //音频详情
    courseId: '',//课程id
    commentId: '',//评论id
    contentRateShow: [],//评价
    audioLoading: true,
    //我的评论
    commentData: [],//评论
    Commentotal: 0,
    commentPageNum: 1,
    commentPageSize: 12,
    commentLength: true,
    refreshDetai: '',
    commentMoenyRank: [],

    rewardReplyText:'你说的太好了，赏！',//打赏评论回复

    scoreData: [],//评分
    updateStatus: '',//单集/连载
    coursePrice: 0,//是否需要购买
    buyCoursesLoading: false,
    rewardMoney: '',//打赏金额
    rewardVisible: false,
    rewardType: '',
    // getCurrentList(_music)
    //播放配置
    playTestingStatus: true,//判断是否正在播放
    setInterValSid: false,//定时是否禁用
    audoId: document.getElementById('audioElement'),//播放器id
    playStatusoError: false,//播放异常
    detaiLocationSearch: '',//记录当前网页url地址
    audoMsg: '',//audio提示,
    audoMsgText: '',
    audoPusenStatus: false,//暂停之后，离开页面清除
    //课程表数
    currentTime: 0, //当前课程播放的时间
    currentTotalTime: 0, //当前课程的总时间
    playStatus: false, //true为播放状态，false为暂停状态       


    //勾选统计
    checkoutNum: 0,
    checkoutPrice: 0,
    //评论
    commentVisible: false,
    contentItems: [],
    commentText: '',


    //第二版改动
    //音频详情
    itemId: '',
    inType:'',
    audioData: {},
    //课程详情
    courseInfo: {},//课程信息
    courseItem: [],

    commentLoading:true,//初始数据判断
    commentListStatus:true,//初始轮训数据判断
  },
  reducers: {
    save(state, action) {
      return {
        ...state, ...action.payload
      };
    },
  },
  effects: {
    //audio url切换
    *audioSetUrl({ payload, dispatch }, { call, put, select }) {
      const { audoId, audioData, playStatus } = yield select(state => state.detai);

      let { item_id } = audioData
      //拿到音频id请求播放地址
      const { data } = yield call(detaiService.getCourseAddr, {
        item_id
      })
      if (data.status == 200) {
        audoId.setAttribute('src', data.data.audio_addr);
        // yield put({ type: 'save', payload: { playTestingStatus: true } });
        yield call(detaiService.savePlayNum, { item_id })

        // yield put({ type: 'updatePlayStatus', dispatch });
        audoId.onerror = function () {
          //重新恢复播放状态
          // dispatch({ type: 'detai/save', payload: { playStatus: false, playStatusoError: true, playTestingStatus: false } });
          if (navigator && navigator.onLine === false) {
            // dispatch({type:'detai/save',payload:{audoMsg:'网络异常/原因：网络未连接或者超时'}});
            Toast.info('网络异常/原因：网络未连接或者超时', 1)
          } else {
            // Toast.info('音频URL链接失效', 1)
          }
          // audoId.currentTime = 0;
          // console.log('url地址异常');
          //2.失败时，直接播放下一个
          // dispatch({type:'detai/playNext',payload:{type:'next'},dispatch});
        };
      } else {
        Toast.info(data.msg, 1)
      }

    },
    //更新播放状态
    *updatePlayStatus({ payload, dispatch }, { call, put, select }) {
      const { audoId, playTestingStatus, playStatusoError } = yield select(state => state.detai);
      //手动出现异常时处理
      if (playStatusoError) {
        //1.点下一个播放失败时回退，保持当前课程，等待是否网络问题，可以重新播放当前课程
        audoId.load();
        yield put({ type: 'save', payload: { playStatusoError: false } });
      }
      if (playTestingStatus) {
        yield put({ type: 'save', payload: { playTestingStatus: true, playStatus: !playStatusoError && true } })
        audoId.play();
      } else {
        yield put({ type: 'save', payload: { playTestingStatus: false, playStatus: !playStatusoError && false } })
        audoId.pause();
      }
    },

    //播放/暂停
    *audioToogle({ payload, dispatch }, { call, put, select }) {
      const { audoId, playTestingStatus, currentTime, audioData } = yield select(state => state.detai);
      let { item_id, is_buy, type } = audioData;
      //判断当前音频是否购买过
      if (type == 2 || is_buy == 2) {
        //判断是否有正在播放的课程
        yield put({ type: 'save', payload: { playTestingStatus: payload.playTestingStatus, playStatus: payload.playTestingStatus } })

        if (audoId.getAttribute('src') == null) {
          yield put({ type: 'audioSetUrl', dispatch });
        } else {
          yield put({ type: 'updatePlayStatus', dispatch });
        }
      } else {
        Toast.info('你未购买该课程，请前往购买', 1);
        audoId.currentTime = 0;
        yield put({ type: 'save', payload: { playTestingStatus: false } })
      }
    },



    //播放暂停
    *togglePlay({ payload }, { call, put, select }) {
      const { audoId, audioData, playTestingStatus, detaiLocationSearch } = yield select(state => state.detai);
      let { item_id, is_buy, type } = audioData;
      //判断当前音频是否购买过
      if (type == 2 || is_buy == 2) {
        //当前音频地址为空时，请求地址
        if (audoId.getAttribute('src') == null || detaiLocationSearch && detaiLocationSearch != item_id) {
          audoId.currentTime = 0;
          yield put({ type: 'save', payload: { currentTime: 0, detaiLocationSearch: '', playTestingStatus: true, playStatus: true } })
          // audoId.load();
          //拿到音频id请求播放地址
          const { data } = yield call(detaiService.getCourseAddr, { item_id })
          if (data.status == 200) {
            // audoId.setAttribute('src', 'https://m10.music.126.net/20180807182047/67a3292fd9b9f4627aa9c12abc293807/ymusic/7ff4/9a4e/15e6/a4dee4bd587604dd598f7b2186ffd4cd.mp3');
            audoId.setAttribute('src', data.data.audio_addr);
            //记录播放
            yield call(detaiService.savePlayNum, { item_id });
            //开始播放
            // if (audoId.paused) {
            yield put({ type: 'togglePlayStatus', payload: { status: true } });
            // }
          }
        } else {
          //当前已有音频地址
          yield put({ type: 'togglePlayStatus', payload: { status: playTestingStatus } });
        }
      } else {
        Toast.info('你未购买该课程，请前往购买', 1);
      }
    },
    //更新播放状态
    *togglePlayStatus({ payload }, { call, put, select }) {
      const { audoId, audioData } = yield select(state => state.detai);
      if (payload.status) {
        audoId.play();
        yield put({ type: 'save', payload: { setInterValSid: true, detaiLocationSearch: audioData.item_id } });
        yield put({ type: 'updateTimeout' });
        document.getElementById('audioPlayIcon').style.display = 'none';
        // alert('播放')
      } else {
        // alert('暂停')
        audoId.pause();
        yield put({ type: 'save', payload: { setInterValSid: false, detaiLocationSearch: '' } });
      }
      yield put({ type: 'save', payload: { playTestingStatus: payload.status, playStatus: payload.status, audoPusenStatus: payload.status } });
    },

    //播放定时更新
    *updateTimeout({ payload }, { call, put, select }) {
      const { audoId, setInterValSid, currentTime, currentTotalTime } = yield select(state => state.detai);
      if (navigator && navigator.onLine === false) {
        // dispatch({type:'detai/save',payload:{audoMsg:'网络异常/原因：网络未连接或者超时',playStatus:false,playStatusoError:true}});
      }
      //播放完停止
      if (parseInt(audoId.currentTime) * 1000 == parseInt(currentTotalTime)) {
        yield put({ type: 'save', payload: { setInterValSid: false, playTestingStatus: false, playStatus: false } });
      }

      if (setInterValSid) {
        yield put({
          type: 'save',
          payload: {
            currentTime: audoId.currentTime,
          }
        })
        yield call(delayDetai, 1000);
        yield put({ type: 'updateTimeout' });
      }


    },

    //判断全局播放是否再播放
    *globalPlayStatus({ payload }, { call, put, select }) {
      const { detaiLocationSearch, playTestingStatus } = yield select(state => state.detai);
      if (detaiLocationSearch == payload.id) {
        yield put({
          type: 'save',
          payload: {
            commentVisible: false,
            commentLength: true,
            commentPageNum: 1,
            commentData: [],
            audioLoading: false,
            setInterValSid: true, playTestingStatus: true, playStatus: true
          }
        })
        yield put({ type: 'updateTimeout' })
        document.getElementById('audioPlayIcon').style.display = 'none';
        //重新渲染拿数据   
      } else {
        // if (detaiLocationSearch == payload.url) {
        // if(playTestingStatus){
        yield put({ type: 'save', payload: { setInterValSid: false, currentTime: 0, playTestingStatus: false, playStatus: false } })
        // }

        // } else {
        // yield put({ type: 'save', payload: { setInterValSid: false, currentTime: 0, playStatus: false, playTestingStatus: false, } })
        // }
      }
      yield put({ type: 'getAudioInfo' });
    },


    //获取课程详情
    *getCourseInfo({ payload, getLoading }, { call, put, select }) {
      const { courseId } = yield select(state => state.detai);
      const { data } = yield call(detaiService.getCourseInfo, {course_id:courseId});
      if (data.status == 200) {
        var itemIds = [];
        data.data.course_item.map((d, i) => {
          if(d.type == 1 && d.is_buy == 1) {
            itemIds.push(d.item_id)
          }
        })
        const { content, big_pic, course_id, course_title } = data.data.course;
        document.title = course_title;
        yield put({
          type: 'onMenuShare', payload: {
            title: course_title,
            id: course_id,
            img: big_pic,
            brief: content,
            link: '/detai/list?id=' + course_id,
            // link: location.href.split('#')[0] + '#/detai/list?id=' + item_id
          }
        })
        yield put({
          type: 'save',
          payload: {
            courseInfo: {
              ...data.data.course,
              itemIds: itemIds,
            },
            courseItem: data.data.course_item || [],
          }
        })
      }
    },
    //获取音频详情
    *getAudioInfo({ payload }, { call, put, select }) {
      const { playTestingStatus, itemId,inType,commentListStatus } = yield select(state => state.detai);
      const { data } = yield call(detaiService.getAudioInfo, { item_id: itemId,in_type:inType });
      if (data.status == 200) {
        //先拿到音频数据，然后获取评论数据，默认判断跳转tab卡需要的条件
        if(commentListStatus){
         yield put({ type: 'audioComment' });
        }
        // data.data.audio.audio_duration=Number(data.data.audio.audio_duration * 1000);
        yield put({
          type: 'save',
          payload: {
            commentListStatus:false,
            audioData: data.data.audio,
            currentTotalTime: Number(data.data.audio.audio_duration * 1000),//获取当前默认第一个单集的时间
            // currentTotalTime: 279000
          }
        })
        const { title, item_id, audio_pic, audio_brief } = data.data.audio;
        document.title = title;
        yield put({
          type: 'onMenuShare', 
          payload:{
            title: title,
            id: item_id,
            img: audio_pic,
            brief: audio_brief,
            link: '/detai?id=' + item_id
          }
        })
        // dispatch({ type: 'detai/updateTimeout', dispatch });
      }
    },
    //分享内容
    *onMenuShare({ payload, dispatch }, { call, put, select }) {
      wx.ready(() => {
        //分享朋友圈
        wx.onMenuShareTimeline({
          title: payload.title, // 分享标题
          link: window.location.href.split('#')[0] + '?t=' + payload.link, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
          imgUrl: payload.img, // 分享图标
          success: function () {
            alert('分享成功')
            // 用户点击了分享后执行的回调函数
          }
        });
        //分享给朋友
        wx.onMenuShareAppMessage({
          title: payload.title, // 分享标题
          desc: payload.brief, // 分享描述
          link: window.location.href.split('#')[0] + '?t=' + payload.link, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
          imgUrl: payload.img, // 分享图标
          type: '', // 分享类型,music、video或link，不填默认为link
          dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
          success: function () {
            alert('分享成功')
          }
        });
      })
    },
    //获取评论列表
    *audioComment({ payload, commentUpdate }, { call, put, select }) {
      const { commentPageNum, commentPageSize, commentData, commentLoading,itemId, refreshDetai,inType,audioData } = yield select(state => state.detai);

      const { data } = yield call(detaiService.audioComment, { item_id: itemId, page: commentPageNum, pageSize: commentPageSize });
      if (data.status == 200) {
        //评论日期转换
        let commentList = [];
        data.data.comment.map((d, i) => {
          commentList.push({
            ...d,
            created_at: getDateDiff(d.created_at * 1000)
          })
        })
        //评论少于5条进入到故事正文(文章免费，已购买)audioData.is_buy == 1 && audioData.type == 1 && inType==1
        if(data.data.comment.length<5 && inType==1 && commentPageNum==1 && commentLoading){
          audioData.type == 2 || audioData.is_buy == 2?yield put({ type: 'save', payload: {tabKey:3}}):null;
        }
         //评论少于5条进入到故事简介(收费文章，未购买)
        if(data.data.comment.length<5  && inType==1 && commentPageNum==1 && commentLoading){
          audioData.is_buy == 1 && audioData.type == 1?yield put({ type: 'save', payload: {tabKey:1}}):null;
        }
        audioData
        data.data.money_rank.map((d, i) => {
          d.created_at = getDateDiff(d.created_at * 1000)
        })
        commentData.push(...commentList)
        yield put({ type: 'save', payload: { audioLoading: false,commentMoenyRank: data.data.money_rank, commentLoading:false,commentData: commentUpdate ? commentList : commentData, Commentotal: data.data.total, commentLength: data.data.comment.length < commentPageSize ? false : true, commentPageNum: commentPageNum + 1 } })
        refreshDetai && refreshDetai()
      }
    },
    //发表评论及打分
    *comment({ payload, getLoading, commentUpdate }, { call, put, select }) {
      const { rewardType } = yield select(state => state.detai);
      const { data } = yield call(detaiService.comment, payload);
      if (data.status == 200) {
        yield put({ type: 'save', payload: { commentVisible: false, commentText: '' } })
        rewardType==2 && Toast.info('发表成功', 1);
        if (commentUpdate) {
          yield put({ type: 'audioComment', commentUpdate })
        } else {
          getLoading && getLoading();
        }
      } else {
        Toast.info(data.msg, 1)
      }
    },
     *takeAuction({ payload }, { call, put, select }) {
      const { audioData} = yield select(state => state.detai);
      const { data } = yield call(detaiService.takeAuction, payload);
      if (data.status == 200) {
        alert('申请成功');
        audioData.auction_status=2;
        yield put({type:'save',payload:{audioData}})
      } else {
        Toast.info(data.msg, 1)
      }
    },
    //评论点赞及取消
    *likeComment({ payload }, { call, put, select }) {
      const { commentData,commentMoenyRank } = yield select(state => state.detai);
      const { data } = yield call(detaiService.likeComment, payload);
       console.log(payload)
      if (data.status == 200) {
         if(payload.typeCatey=='1'){

          //最热评论
            commentMoenyRank[payload.index].is_star = payload.type;
          if(Number(commentMoenyRank[payload.index].like_num)!=0){
            commentMoenyRank[payload.index].like_num = payload.type==1?Number(commentMoenyRank[payload.index].like_num)+1:Number(commentMoenyRank[payload.index].like_num)-1;
          }else{
            commentMoenyRank[payload.index].like_num = payload.type==1?1:0;
          }
        }else{
          //最新评论
          commentData[payload.index].is_star = payload.type;
          if(Number(commentData[payload.index].like_num)!=0){
            commentData[payload.index].like_num = payload.type==1?Number(commentData[payload.index].like_num)+1:Number(commentData[payload.index].like_num)-1;
          }else{
            commentData[payload.index].like_num = payload.type==1?1:0;
          }
        }
        yield put({ type: 'save', payload: { commentData,commentMoenyRank } })
        // if (payload.type == 1) {
        //   Toast.info('点赞成功', 1)
        // } else {
        //   Toast.info('取消点赞', 1)
        // }
        // Toast.info(data.msg, 1)
      } else {
        Toast.info(data.msg, 1)
      }
    },
    //购买课程
    *buyAudio({ payload, dispatch }, { call, put, select }) {
      const { data } = yield call(detaiService.buyAudio, payload);
      yield put({ type: 'save', payload: { buyCoursesLoading: true } })
      if (data.status == 200) {
        yield put({
          type: 'createWeixinPay',
          payload: {
            ...data.data.weixin_pay
          },
          dispatch,
          msg: '购买成功'
        })
        yield put({ type: 'save', payload: { buyCoursesLoading: false } })
      } else {
        yield put({ type: 'save', payload: { buyCoursesLoading: false } })
        Toast.info(data.msg, 1)
      }
    },
    //对评论或课程打赏
    *reward({ payload, dispatch }, { call, put, select }) {
      const { data } = yield call(detaiService.reward, payload);
      if (data.status == 200) {
        yield put({
          type: 'save',
          payload: {
            rewardMoney: '',
            commentId: '',
            rewardVisible: false,
          }
        })
        yield put({
          type: 'createWeixinPay',
          payload: {
            ...data.data.weixin_pay
          },
          dispatch,
          msg: '打赏成功'
        })
      } else {
        Toast.info(data.msg, 1)
      }
    },
    //获取微信分享配置
    *weixinShareConfig({ payload, dispatch }, { call, put, select }) {
      const { data } = yield call(detaiService.weixinShareConfig, payload)
      if (data && data.status == 200) {
        /*验证微信接口授权*/
        wx.config({
          ...data.data,
          debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
          // appId: '', // 必填，公众号的唯一标识
          // timestamp: '', // 必填，生成签名的时间戳
          // nonceStr: '', // 必填，生成签名的随机串
          // signature: '', // 必填，签名，见附录1
          // jsApiList: [] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
        });
      }
    },
    /*发起微信支付*/
    *createWeixinPay({ payload, dispatch, msg }, { call, put, select }) {
      const { rewardType,audioData,rewardReplyText} = yield select(state => state.detai);
      const {commentListPlay}=yield select(state=>state.comment)
      const onBridgeReady = () => {
        WeixinJSBridge.invoke(
          'getBrandWCPayRequest', payload, (res) => {
            console.log('res', res)
            if (res.err_msg == "get_brand_wcpay_request:ok") {
              dispatch({ type: 'detai/save', payload: { commentLength: true, commentPageNum: 1 } });
              //对课程购买
              rewardType==3 && dispatch({ type: 'detai/getCourseInfo' })

              //对作者打赏
              rewardType==2 && rewardReplyText && dispatch({
                  type: 'detai/comment',
                  payload: {
                      course_items_id: audioData.item_id,
                      content: rewardReplyText,
                  },
                  commentUpdate: true
              });
              
              //对评论打赏
              rewardType==1 && dispatch({ type: 'detai/audioComment', commentUpdate: true });
              dispatch({ type: 'detai/getAudioInfo' })
               //对该评论人回复打赏
              if(commentListPlay){
                 dispatch({type:'comment/save',payload:{ 
                   commentReply:[],
                   commentPageNum:1
                 }});
                 dispatch({
                   type:'comment/getCommentReplyList'
                 })
              }
              alert('支付提示', <Result
                img={<Icon type="check-circle" className="spe" style={{ fill: '#09BB07', width: '1.5rem', height: '1.5rem' }} />}
                title={msg}
                message={null}
              />, [
                  { text: '确定', onPress: () => { } },
                ])
            } // 使用以上方式判断前端返回,微信团队郑重提示：res.err_msg将在用户支付成功后返回    ok，但并不保证它绝对可靠。 
          }
        );
      }
      if (typeof WeixinJSBridge == "undefined") {
        if (document.addEventListener) {
          document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
        } else if (document.attachEvent) {
          document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
          document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
        }
      } else {
        onBridgeReady();
      }
    },
  },
  subscriptions: {
    setup({ dispatch, history }) {
      return history.listen(({ pathname, query }) => {
        if (pathname == '/detai/list') {
          document.title = '人人编剧';
          // dispatch({ type: 'save', payload: { courseId: query.id } });
          dispatch({ type: 'save', payload: { courseId: query.id } })
          dispatch({ type: 'getCourseInfo'})
        }
        if (pathname == '/detai') {
          document.title = '人人编剧';
          dispatch({ type: 'save', payload: { itemId: query.id,inType:query.in_type==2?2:1,tabKey:query.in_type==1?2:1, dispatch } });
          dispatch({ type: 'globalPlayStatus', payload: { id: query.id } })
        }
        if (pathname == '/detai' || pathname == '/detai/list' || pathname=='/release') {
          dispatch({ type: 'weixinShareConfig', payload: { url: location.href.split('#')[0] } })
        }
        dispatch({ type: 'save', payload: { audoId: document.getElementById('audioElement'), audoMsgText: '', commentVisible: false, contentItems: [], commentText: '', buyCoursesLoading: false, rewardVisible: false } })
        //离开后是否禁用定时器
        // dispatch({ type: 'save', payload: { setInterValSid: pathname != '/detai' ? false : true } })
      });
    },
  },
};
