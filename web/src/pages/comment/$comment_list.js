import { Component } from 'react';
import { connect } from 'dva';
import { ActivityIndicator ,Modal,Toast} from 'antd-mobile';
import { routerRedux } from 'dva/router';
import {getDateDiff,onMax, normalizeCurrency } from '../../utils';
import './index.less';
import CommentList from '../../components/list'
import Commentsub from '../detai/components/comment';
/**
*--评论回复列表
*--2018.09.02 anaoei@qq.com
* @method Comment 文件名
* @param { ReactElement } Comment 组件名
* @param { String } comment model名称
*/
function mapStateToProps(props) {
    return {
        comment: props.comment,
        loading: props.loading,
        detai:props.detai
    };
}

class Comment extends Component {
    
    componentDidMount() {
        this.props.dispatch({ type: 'comment/save',payload:{commentId:this.props.match.params.comment_list,commentLoading:true,commentListPlay:true,} });
        this.props.dispatch({ type: 'comment/getCommentReplyList',payload:{comment_id:this.props.match.params.comment_list} });
    }

    componentWillUnmount() {
      this.props.dispatch({
        type:'comment/save',
        payload:{
           commentLength:true,
           commentReply:[],
           oneComment:[],
           commentTotal:0,
           commentPageNum:1,
           commentLike:[],
           commentReward:[],
           commentListPlay:false
        }
      })
    }
     //对评论或者课程打赏
    onBuyCourses = (type) => {
        const { commentId, rewardMoney, rewardType } = this.props.detai;
        const { dispatch } = this.props;
        if (Number(rewardMoney)) {
            if(Number(rewardMoney)>=0.1){
            dispatch({
                type: 'detai/reward',
                payload: {
                    course_items_id:'',
                    comment_id: rewardType == 1 ? commentId : '',
                    type: rewardType,
                    money: rewardMoney
                },
                dispatch,
                playLoading: () => dispatch({ type: 'comment/getCommentReplyList' })
            })
            }else{
                Toast.info('打赏金额不得少于0.1', 1)
            }
             
        } else {
            Toast.info('请输入打赏金额', 1)
        }
    }
    render() {
        const { comment, location,loading } = this.props;
        const {dispatch}=this.props;
        // // let moeny = location.query.money;
        // const routerPush = (routerName, query) => {
        //     dispatch(routerRedux.push({
        //         pathname: routerName,
        //         query
        //     }));
        // }
        const { commentLength,commentReply,oneComment,commentId,commentLoading} = comment;
        const {rewardVisible,rewardMoney}=this.props.detai;
        const listData={
            actionType:'comment/save',
            loading:commentLoading,
            listLength:commentLength,
            onChangeGet:()=>{
                 dispatch({ type: 'comment/getCommentReplyList'})
            },
        }
        return (
            <div className="userDetai fadeIn animated">
               <CommentList {...listData}>
                <div>
                     <div className="comment" style={{ paddingTop: '0.2rem' }}>
                            {/* {
                            audioData.is_buy == 2 || audioData.type == 2 ?
                                <div className="commentEdit"><span onClick={() => dispatch({ type: 'detai/save', payload: { commentVisible: true } })}>写评价</span></div>
                                : null
                        } */}
                            <ul>
                               <li>
                                <span className="user"><img src={oneComment.headimgurl} /></span>
                                <div className="title">
                                    <div className="left"> <span>{oneComment.nickname}</span>{Number(oneComment.money || 0) ? <span className="price">￥{oneComment.money}</span> : null}</div>
                                    <div className="right"> <span onClick={() => dispatch({
                                        type: 'detai/save',
                                        payload: {
                                            rewardVisible: true,
                                            rewardType: 1,
                                            commentId: commentId
                                        }
                                    })}></span>
                                        <span onClick={() => dispatch({ type: 'comment/likeComment', payload: { comment_id: commentId, type: oneComment.is_star == 1 ? 2 : 1} })} className={oneComment.is_star == 1 ? 'active' : ''} style={{color:oneComment.is_star == 1 ? '#E2B979' : '#999'}}>{Number(oneComment.likeTotal)?oneComment.likeTotal > 10000 ? ((oneComment.likeTotal - oneComment.likeTotal % 1000) / 10000 + 'W') : oneComment.likeTotal:''}</span></div>
                                </div>
                                <div className="nr">{oneComment.content}</div>
                                <div className="date">{getDateDiff(Number(oneComment.created_at * 1000) || 0)}</div>
                                {
                                    oneComment.length!=0 && oneComment.likeDetail.length!=0?
                                <div className="userTag" onClick={() => dispatch(routerRedux.push({
                                    pathname: `/comment/like_list/${commentId}`,
                                }))}>
                                {
                                    oneComment.length!=0 && oneComment.likeDetail.map((d,i)=><span key={i}><img src={d.headimgurl}/></span>)
                                }
                                <font>{oneComment.likeTotal?oneComment.likeTotal+'人赞过>':''}</font>
                                </div>
                                :
                                <div className="userTag"><p>暂无人点赞</p></div>
                                }
                                {
                                    oneComment.length!=0 && oneComment.rewardDetail.length!=0?
                                <div className="userTag" onClick={() => dispatch(routerRedux.push({
                                    pathname: `/comment/reward_list/${commentId}`,
                                }))}>
                                {
                                    oneComment.length!=0 && oneComment.rewardDetail.map((d,i)=><span key={i}><img src={d.headimgurl}/></span>)
                                }
                                <font>{oneComment.rewardTotal?oneComment.rewardTotal+'人打赏过>':''}</font>
                                </div>
                                : <div className="userTag"><p>暂无人打赏</p></div>}
                            </li>
                            </ul>
                            <ul>
                                {
                                    commentReply && commentReply.map((d, i) => {
                                        return (
                                            <li key={i}>
                                                <span className="user"><img src={d.headimgurl} /></span>
                                                <div className="title">
                                                    <div className="left"> <span>{d.nickname}</span>{Number(d.money || 0) ? <span className="price">￥{d.money}</span> : null}</div>
                                                    {/*<div className="right"> <span onClick={() => dispatch({
                                                        type: 'detai/save',
                                                        payload: {
                                                            rewardVisible: true,
                                                            rewardType: 1,
                                                            commentId: d.id
                                                        }
                                                    })}></span>
                                                        <span onClick={() => dispatch({ type: 'detai/likeComment', payload: { comment_id: d.id, type: d.is_star == 1 ? 2 : 1, index: i } })} className={d.is_star == 1 ? 'active' : ''}></span></div>*/}
                                                </div>
                                                <div className="nr">{d.content}</div>
                                                <div className="date">{d.created_at}</div>
                                            </li>
                                        )
                                    })
                                }
                                {
                                    commentReply.length != 0 ?
                                        <div className="moreStyle">{commentLength ? loading.effects['comment/getCommentReplyList'] ? '正在加载' : '加载更多' : '没有更多评论了'}</div>
                                        : null
                                }

                            </ul>
                        </div>
                     </div>
                </CommentList>

                            {
                                commentReply.length == 0 && !loading.effects['comment/getCommentReplyList']?
                                    <div style={{ background: "#fff" }}>
                                        <div className="lengthIcon"></div>
                                        <p className="lengthIcontext" style={{ marginBottom: '0.4rem' }}>暂无回复列表！</p>
                                        <br />
                                    </div>
                                    : null
                            }
                            {/* 打赏 */}
                            <Modal
                                visible={rewardVisible}
                                transparent
                                style={{ width: '8rem' }}
                                className="rewardModal"
                                onClose={() => dispatch({ type: 'detai/save', payload: { rewardMoney: '', rewardVisible: false } })}
                                title='对该网友打赏'
                                footer={[{ text: '取消', onPress: () => dispatch({ type: 'detai/save', payload: { rewardMoney: '', rewardVisible: false } }) }, { text: this.props.loading.effects['detai/reward'] ? '正在支付' : '打赏', onPress: !this.props.loading.effects['detai/reward'] ? this.onBuyCourses : null }]}
                            >
                                <div className="rewardInput">
                                    <span style={{ fontSize: '0.7rem', verticalAlign: 'middle' }}>￥</span>&nbsp;<input type="text" value={rewardMoney} onChange={e => dispatch({ type: 'detai/save', payload: { rewardMoney: normalizeCurrency(e.target.value) } })} onInput={e => onMax(e, 6)} />
                                    {rewardMoney ? '' : <span className="rewardPlaceholder">输入金额</span>}
                                </div>
                            </Modal>

                            <ul className="homeFixed" style={{ bottom:'0.4rem' }}>
                             <li className="commentIcon" onClick={() => dispatch({ type: 'detai/save', payload: { commentVisible: true } })}></li>
                            </ul>
                            <Commentsub popup={true} commentSubmit={(val, roteVal) => {
                                dispatch({
                                    type: 'comment/save', payload: {
                                        commentLength: true,
                                        commentPageNum: 1
                                    }
                                });
                                dispatch({
                                    type: 'comment/commentReply',
                                    payload: {
                                        comment_id: commentId,
                                        content: val,
                                    },
                                    commentUpdate: true
                                })
                            }} />
            </div>
        )
    }
}
export default connect(mapStateToProps)(Comment)
