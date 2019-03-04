
import React from 'react';
import { connect } from 'dva';
import { routerRedux } from 'dva/router';
import { Modal, ActivityIndicator, Toast ,TextareaItem} from 'antd-mobile';
// import Musicsub from './components/music';
import Commentsub from './components/comment';
import { onMax, normalizeCurrency } from '../../utils';
import ProgressBar from './components/ProgressBar'
import './index.less'
const alert = Modal.alert;
/**
*--详细页（内容展示）
*--2018.07.13 anaoei@qq.com
* @method DetaiIndex 文件名
* @param { ReactElement } DetaiSub 组件名
* @param { String } detai model名称
*/

function mapStateToProps(props) {
    return {
        detai: props.detai,
        loading: props.loading
    };
}

let audoMsgText = '';
//更新組件
class IscrollDetai extends React.Component {
    componentDidMount() {
        setTimeout(() => {
            this.props.refreshDetai != undefined && this.props.refreshDetai()
        }, 400)
    }
    render() {
        const { children } = this.props;
        return (
            <div>
                {children}
            </div>
        )
    }
}
class DetaiSub extends React.Component {
    constructor(props, context) {
        super(props, context);
        this.state = {
            checkoutStatus: false,
            // tabKey: 2,
            rote: [1, 2, 3, 4, 5],
            roteVal: {
                structure: 4.5,
                character: 3.8,
                plot: 2.8
            }
        };
        this.onScrollEnd = this.onScrollEnd.bind(this);
    }
    componentDidMount() {
        const { dispatch } = this.props;
        // encodeURIComponent(location.href.split('#')[0])

        // dispatch({ type: 'detai/getAudioInfo' });
        // inType
        
        this.move = window.document.ontouchmove;//保存默认触摸事件
        //禁止触摸
        window.document.ontouchmove = function (e) {
            e.preventDefault();
        };
        audoMsgText = '';
        //阻止上下拉滚动效果
        //阻止上下拉滚动效果
        this.onTouchmove = function (e) { e.preventDefault(); }
        document.body.addEventListener('touchmove', this.onTouchmove, { passive: false });


        document.getElementById('DetaiCommentList').style.height = document.documentElement.clientHeight + 'px';
        const options = {
            // 默认iscroll会拦截元素的默认事件处理函数，我们需要响应onClick，因此要配置
            preventDefault: false,
            // 禁止缩放
            zoom: false,
            // 支持鼠标事件，因为我开发是PC鼠标模拟的
            mouseWheel: true,
            // 滚动事件的探测灵敏度，1-3，越高越灵敏，兼容性越好，性能越差
            probeType: 3,
            // 拖拽超过上下界后出现弹射动画效果，用于实现下拉/上拉刷新
            bounce: true,
            killFlicker: true,
        };
        this.iScrollInstance = new IScroll(document.getElementById('DetaiCommentList'), options);
        this.iScrollInstance.on('scrollEnd', this.onScrollEnd);
        dispatch({ type: 'detai/save', payload: {  audioLoading: true,refreshDetai: () => this.iScrollInstance.refresh() } });
    }
    onScrollEnd() {
        // 滑动结束后，停在加载区域
        const { commentLength, refreshDetai,tabKey } = this.props.detai;
        const { dispatch } = this.props;
        if (this.iScrollInstance.y <= this.iScrollInstance.maxScrollY && commentLength && tabKey == 2) {
            dispatch({ type: 'detai/audioComment' })
        }
    }
    componentWillUnmount() {
        const { audoId, playTestingStatus, audoPusenStatus } = this.props.detai;
        window.document.ontouchmove = this.move;
        document.body.removeEventListener('touchmove', this.onTouchmove, { passive: false });
        this.iScrollInstance.destroy();
        if (playTestingStatus) {
            document.getElementById('audioPlayIcon').style.display = 'block';
        }
        if (!audoPusenStatus) {
            audoId.removeAttribute('src');
            audoId.currentTime = 0;
            audoId.load();
            this.props.dispatch({
                type: 'detai/save',
                payload: {
                    detaiLocationSearch: '',
                }
            })
        }
        this.props.dispatch({
            type: 'detai/save',
            payload: {
                commentVisible: false,
                commentLength: true,
                commentPageNum: 1,
                commentData: [],
                refreshDetai: '',
                commentMoenyRank:[],
                audioLoading: true,
                commentLoading:true,
                commentListStatus:true
            }
        })
    }
    componentWillReceiveProps(nextProps) {
        let { loading,detai, } = nextProps;
        // if (!loading.effects['detai/audioComment']) {
        //      //评论少于5条进入到
        //     if(detai.commentData.length<5 && detai.inType==1){
        //           // this.props.dispatch({type:'detai/save',payload:{tabKey:3}})
        //        if (detai.audioData.type == 1 && detai.audioData.is_buy == 1) {
        //           // this.props.dispatch({type:'detai/save',payload:{tabKey:1}})
        //        }
        //     }
          
        // }
    
      }
    //tab切换
    onTabkey(key) {
        const { audioData, refreshDetai,inType } = this.props.detai;
        if (key == 3 && inType==1) {
            if (audioData.type == 2 || audioData.is_buy == 2) {
               this.props.dispatch({type:'detai/save',payload:{tabKey:3}})
            } else { alert('正文需付费，请先购买') };
        } else {
            this.props.dispatch({type:'detai/save',payload:{tabKey:key}})
        }
        this.iScrollInstance.scrollTo(0, 0);
    }

    //购买支付
    onClickBuy = (sig, index) => {
        const { audioData } = this.props.detai;
        const { dispatch } = this.props;

        dispatch({
            type: 'detai/buyAudio',
            payload: {
                buy_type: 1,
                courseItemsIds: [audioData.item_id],
            },
            dispatch,
            playLoading: () => dispatch({ type: 'detai/getAudioInfo' })
        })
    }
    //对评论或者课程打赏
    onBuyCourses = (type) => {
        const { audioData, commentId, rewardMoney, rewardType } = this.props.detai;
        const { dispatch } = this.props;
        if (Number(rewardMoney)) {
            if(Number(rewardMoney)>=0.1){
                dispatch({
                    type: 'detai/reward',
                    payload: {
                        course_items_id: rewardType == 2 ? audioData.item_id : '',
                        comment_id: rewardType == 1 ? commentId : '',
                        type: rewardType,
                        money: rewardMoney
                    },
                    dispatch,
                    playLoading: () => {}
                })
            }else{
                Toast.info('打赏金额不得少于0.1', 1)
            }
        } else {
            Toast.info('请输入打赏金额', 1)
        }
    }

    render() {
        const { checkoutStatus, rote, roteVal } = this.state;
        const { audioData, refreshDetai,tabKey, audioLoading,rewardReplyText, commentData, commentMoenyRank, Commentotal, coursePrice, buyCoursesLoading, updateStatus, item, audoMsg, audoId, currentTime, playStatus, currentTotalTime, playTestingStatus, commentVal, contentItems, contentRateShow, commentVisible, rewardVisible, rewardMoney, rewardType, commentLength,inType } = this.props.detai;
        const { dispatch, loading } = this.props;
        const progressData = {
            percentage: currentTime / currentTotalTime * 1000,//当前进度条
            percentageChangeFunc: (percentage) => {
                if(audioData.have_audio==1){
                    if (audioData.type == 2 || audioData.is_buy == 2) {
                    let duration = currentTotalTime / 1000;
                    let currentTimeUpdate = duration * percentage;
                    audoId.currentTime = currentTimeUpdate;
                    dispatch({
                        type: 'detai/save',
                        payload: { currentTime: currentTimeUpdate, playTestingStatus: true }
                    })
                    dispatch({ type: 'detai/togglePlay' })
                } else {
                    Toast.info('你未购买该课程，请前往购买', 1);
                }
            }else{
                Toast.info('此文章暂无音频', 1);
            }
            }
        }

        //检查播放器
        if (audoId.readyState > 1) {
            audoId.ontimeupdate = () => {
                audoMsgText = '正在播放'
                // audoId.readyState == 4 && console.warn('正在缓冲：' + Math.round(audoId.buffered.end(0) / audoId.duration * 100) + '%');
            }
        } else {
            audoMsgText = '正在缓冲'
        }
        return (
            <div className="DetaiBox">
                <ActivityIndicator
                    toast
                    text="加载数据"
                    animating={audioLoading}
                />
                <ActivityIndicator
                    toast
                    text="正在购买"
                    animating={buyCoursesLoading}
                />
                <div className={`detaiBoxOut ${!audioLoading ? 'fadeIn animated' : ''}`} style={{ paddingBottom: audioData.is_buy == 1 && audioData.type == 1 ? "1.4rem" : 0 }} id="DetaiCommentList">
                    <div>
                        <div className="detaiInfoView">
                            {/* <div className="title">{audioData.title}</div> */}
                            <div className="content">
                                 <div className="left"><img src={audioData.audio_pic} /></div>
                                <div className="center">
                                    <div className="title">{audioData.title}</div>
                                    <div className="box">
                                        <div className="le">
                                            <div className="tag">
                                                <span>{audioData.author}</span>
                                                <span>{audioData.play_num > 10000 ? ((audioData.play_num - audioData.play_num % 1000) / 10000 + 'W') : audioData.play_num || 0}</span>
                                            </div>
                                            <div className="tag">
                                                <span>￥{audioData.reward_amount > 10000 ? ((audioData.reward_amount - audioData.reward_amount % 1000) / 10000 + 'W') : audioData.reward_amount || 0}</span>
                                                <span>{audioData.buy_num > 10000 ? ((audioData.buy_num - audioData.buy_num % 1000) / 10000 + 'W') : audioData.buy_num || 0}人</span>
                                            </div>
                                        </div>
                                        {
                                            audioData.is_original == 2 ?
                                                <div className="ri">
                                                    <div className="right">

                                                        <div className="rewardIcon" onClick={() => dispatch({
                                                            type: 'detai/save',
                                                            payload: {
                                                                rewardVisible: true,
                                                                rewardType: 2
                                                            }
                                                        })}>打赏</div>

                                                    </div>
                                                </div>
                                                : null
                                        }
                                    </div>
                                </div>

                            </div>
                            <div className="line">
                                <div className={`play ${audioData.have_audio==1?playStatus ? "active" : '':'playDisd'}`} onClick={() => {
                                    if(audioData.have_audio==1){
                                        if (audioData.type == 2 || audioData.is_buy == 2) {
                                        dispatch({ type: 'detai/save', payload: { playTestingStatus: !playTestingStatus } })
                                        dispatch({ type: 'detai/togglePlay' })
                                        } else {
                                            Toast.info('你未购买该课程，请前往购买', 1);
                                        }
                                    }else{
                                         Toast.info('此文章暂无音频', 1);
                                    }
                                    

                                }}></div>
                                <div className="pro"> <ProgressBar {...progressData} /><div className="proMsg">{playStatus && audoMsgText}</div></div>
                                <div className="date">
                                    <span style={{ color: '#6B400D' }}>{new Date(currentTime * 1000).Format("mm:ss")}</span>
                                    <span>/&nbsp;{new Date(currentTotalTime).Format("mm:ss")}</span>
                                </div>
                            </div>
                            {
                                audioData.auction_status==4?
                                <div className="acutionTag"><span>拍卖文章</span><span style={{color:'red'}}>拍卖正在火热进行中</span><span className="btn"  onClick={()=>dispatch({type:'detai/takeAuction',payload:{item_id:audioData.item_id}})}>参与竞拍</span></div>
                                :null
                            }
                            {
                                audioData.auction_status==5?
                                <div className="acutionTag"><span>拍卖文章</span><span style={{color:'#9B9B9B'}}>本场拍卖已结束～</span><span style={{color:'red'}}>成交价&nbsp;￥{audioData.auction_money}</span></div>
                                :null
                            }
                        </div>
                        {/* 内容-用户-评分 */}
                        <div className="detaiContent">
                            <ul className="tab">
                                <li className={tabKey == 2 ? 'active' : null} onClick={() => this.onTabkey(2)}>微评({Commentotal})</li>
                                <li className={tabKey == 1 ? 'active' : null} onClick={() => this.onTabkey(1)}>故事简介</li>
                                <li className={tabKey == 3 ? 'active' : null} onClick={() => this.onTabkey(3)}>故事正文</li>
                            </ul>
                            {/* 内容 */}
                            {
                                tabKey == 1 && audioData.audio_brief ?
                                    <IscrollDetai refreshDetai={refreshDetai}>
                                        <div className="introduce" dangerouslySetInnerHTML={{ __html: audioData.audio_brief }}>
                                        </div>
                                    </IscrollDetai>
                                    : null
                            }
                            {
                                tabKey == 1 && audioData.audio_brief == undefined ?
                                    <div style={{ background: "#fff" }}>
                                        <div className="lengthIcon"></div>
                                        <p className="lengthIcontext" style={{ marginBottom: '0.4rem' }}>暂无故事简介</p>
                                        <br />
                                    </div>
                                    : null
                            }
                            {/* 用户 */}
                            {
                                tabKey == 2?
                                    <IscrollDetai refreshDetai={refreshDetai}>
                                        <div className="comment" style={{ paddingTop: '0.2rem' }}>
                                            {/* {
                                            audioData.is_buy == 2 || audioData.type == 2 ?
                                                <div className="commentEdit"><span onClick={() => dispatch({ type: 'detai/save', payload: { commentVisible: true } })}>写评价</span></div>
                                                : null
                                        } */}
                                            {commentMoenyRank.length != 0 ? <div className="commentTitle">最热评论</div> : null}
                                            <ul>
                                                {
                                                    commentMoenyRank.length != 0 && commentMoenyRank.map((d, i) => {
                                                        return (
                                                            <li key={i}>
                                                                <span className="user"><img src={d.head_pic} /></span>
                                                                <div className="title">
                                                                    <div className="left"> <span>{d.nickname}</span>{Number(d.money || 0) ? <span className="price">￥{d.money}</span> : null}</div>
                                                                    <div className="right"> <span onClick={() => dispatch({
                                                                        type: 'detai/save',
                                                                        payload: {
                                                                            rewardVisible: true,
                                                                            rewardType: 1,
                                                                            commentId: d.id
                                                                        }
                                                                    })}></span>
                                                                        <span onClick={() => dispatch({ type: 'detai/likeComment', payload: { comment_id: d.id, type: d.is_star == 1 ? 2 : 1, index: i,typeCatey:1 } })} className={d.is_star == 1 ? 'active' : ''} style={{color:d.is_star == 1 ? '#E2B979' : '#999'}}>{Number(d.like_num)?d.like_num > 10000 ? ((d.like_num - d.like_num % 1000) / 10000 + 'W') : d.like_num:''}</span></div>
                                                                </div>
                                                                <div className="nr">{d.content}</div>
                                                                <div className="date">{d.created_at}&nbsp;&nbsp;&nbsp;<span onClick={() => dispatch(routerRedux.push({
                                                                            pathname: `/comment/${d.id}`,
                                                                        }))} style={{color:'#E2B979'}}>{d.comment_reply?d.comment_reply+'条回复':'回复'}></span></div>
                                                            </li>
                                                        )
                                                    })
                                                }
                                            </ul>
                                            {commentData.length != 0 ? <div className="commentTitle comm">最新评论</div> : null}
                                            <ul>
                                                {
                                                    commentData && commentData.map((d, i) => {
                                                        return (
                                                            <li key={i}>
                                                                <span className="user"><img src={d.head_pic} /></span>
                                                                <div className="title">
                                                                    <div className="left"> <span>{d.nickname}</span>{Number(d.money || 0) ? <span className="price">￥{d.money}</span> : null}</div>
                                                                    <div className="right"> <span onClick={() => dispatch({
                                                                        type: 'detai/save',
                                                                        payload: {
                                                                            rewardVisible: true,
                                                                            rewardType: 1,
                                                                            commentId: d.id
                                                                        }
                                                                    })}></span>
                                                                        <span onClick={() => dispatch({ type: 'detai/likeComment', payload: { comment_id: d.id, type: d.is_star == 1 ? 2 : 1, index: i,typeCatey:2 } })} className={d.is_star == 1 ? 'active' : ''} style={{color:d.is_star == 1 ? '#E2B979' : '#999'}}>{Number(d.like_num)?d.like_num > 10000 ? ((d.like_num - d.like_num % 1000) / 10000 + 'W') : d.like_num:''}</span></div>
                                                                </div>
                                                                <div className="nr">{d.content}</div>
                                                                <div className="date">{d.created_at}&nbsp;&nbsp;&nbsp;<span onClick={() => dispatch(routerRedux.push({
                                                                            pathname: `/comment/${d.id}`,
                                                                        }))} style={{color:'#E2B979'}}>{d.comment_reply?d.comment_reply+'条回复':'回复'}></span></div>
                                                            </li>
                                                        )
                                                    })
                                                }
                                                {
                                                    commentData.length != 0 ?
                                                        <div className="moreStyle">{commentLength ? loading.effects['detai/audioComment'] ? '正在加载' : '加载更多' : '没有更多评论了'}</div>
                                                        : null
                                                }

                                            </ul>
                                        </div>
                                    </IscrollDetai>
                                    : null
                            }
                            {
                                tabKey == 2 && commentData.length == 0?
                                    <div style={{ background: "#fff" }}>
                                        <div className="lengthIcon"></div>
                                        <p className="lengthIcontext" style={{ marginBottom: '0.4rem' }}>暂无评论，赶快抢个沙发吧！</p>
                                        <br />
                                    </div>
                                    : null
                            }
                            {/* 正文 */}
                            {
                                tabKey == 3 && audioData.content ?
                                    <IscrollDetai refreshDetai={refreshDetai}>
                                        <div className="introduce" dangerouslySetInnerHTML={{ __html: audioData.content }}>
                                        </div>
                                    </IscrollDetai>
                                    : null
                            }
                            {
                                tabKey == 3 && audioData.content == undefined ?
                                    <div style={{ background: "#fff" }}>
                                        <div className="lengthIcon"></div>
                                        <p className="lengthIcontext" style={{ marginBottom: '0.4rem' }}>暂无故事正文</p>
                                        <br />
                                    </div>
                                    : null
                            }
                        </div>
                    </div>
                </div>
                {audioData.content != undefined ? <div style={{ height: 0, overflow: 'hidden', fontSize: 0, display: 'none' }} dangerouslySetInnerHTML={{ __html: audioData.content }}></div> : null}
                {
                    audioData.is_buy == 1 && audioData.type == 1 && inType==1 ?
                        <div className="listBtnPlay" onClick={() => this.onClickBuy(false)}>
                            <div className="btn">
                                单集购买&nbsp;￥{audioData.price}元
                        </div>
                        </div>
                        : null
                }
                {
                    !audioLoading && inType==1?
                        <ul className="homeFixed" style={{ bottom: audioData.is_buy == 1 && audioData.type == 1 ? '1.8rem' : '0.4rem' }}>
                            {/* 返回首頁 */}
                            <li className="history" onClick={() => dispatch(routerRedux.push({
                                pathname: '/',
                            }))}></li>
                            {/* 非购买用户评论 */}
                            {
                                audioData.is_buy == 2 || audioData.type == 2 ?
                                    <li className="commentIcon" onClick={() => dispatch({ type: 'detai/save', payload: { commentVisible: true } })}></li>
                                    : null
                            }
                        </ul>
                        : null
                }




                {/* 购买用户在用户订单去评论 */}
                <Commentsub popup={true} commentSubmit={(val, roteVal) => {
                    dispatch({
                        type: 'detai/save', payload: {
                            commentLength: true,
                            commentPageNum: 1
                        }
                    });
                    dispatch({
                        type: 'detai/comment',
                        payload: {
                            course_items_id: audioData.item_id,
                            content: val,
                        },
                        commentUpdate: true
                    })
                }} />

                {/* 打赏 */}
                <Modal
                    visible={rewardVisible}
                    transparent
                    style={{ width: '8rem' }}
                    className="rewardModal"
                    onClose={() => dispatch({ type: 'detai/save', payload: { rewardMoney: '', rewardVisible: false } })}
                    title={rewardType == 2 ? '对该作者打赏' : '对该网友打赏'}
                    footer={[{ text: '取消', onPress: () => dispatch({ type: 'detai/save', payload: { rewardMoney: '', rewardVisible: false } }) }, { text: this.props.loading.effects['detai/reward'] ? '正在支付' : '打赏', onPress: !this.props.loading.effects['detai/reward'] ? this.onBuyCourses : null }]}
                >
                   <div>
                    <div className="rewardInput">
                        <span style={{ fontSize: '0.7rem', verticalAlign: 'middle' }}>￥</span>&nbsp;<input type="text" value={rewardMoney} onChange={e => dispatch({ type: 'detai/save', payload: { rewardMoney: normalizeCurrency(e.target.value) } })} onInput={e => onMax(e, 6)} />
                        {rewardMoney ? '' : <span className="rewardPlaceholder">输入金额</span>}
                    </div>
                    {
                        rewardType == 2?
                         <div>
                         <TextareaItem
                        className="commentText"
                        value={rewardReplyText}
                        rows={3}
                        count={100}
                        onChange={e=>dispatch({type:'detai/save',payload:{rewardReplyText:e}})}
                        placeholder="自定义打赏评论"
                      />
                         </div>:null
                    }
                   
                   </div>
                </Modal>

            </div>
        )
    }
}

export default connect(mapStateToProps, )(DetaiSub)