import React from 'react';
import { connect } from 'dva';
import { ActivityIndicator ,Modal} from 'antd-mobile';
import CommentList from '../../../components/list'

function mapStateToProps(props) {
	return {
        comment: props.comment,
        loading: props.loading,
	};
}

export class RewardList extends React.Component {
	// static propTypes = {
	// 	name: React.PropTypes.string,
	// };
    componentDidMount() {
        console.log(this.props.match.params.reward)
        this.props.dispatch({ type: 'comment/save',payload:{commentId:this.props.match.params.reward} });
        this.props.dispatch({ type: 'comment/commentRewardList',payload:{comment_id:this.props.match.params.reward} });
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
           commentReward:[],
        }
      })
    }
	render() {
		const {loading,dispatch}=this.props;
		const {commentReward,commentLength}=this.props.comment;
		const listData={
           actionType:'comment/save',
            loading:loading.effects['comment/commentRewardList'],
            listLength:commentLength,
            onChangeGet:()=>{
                 dispatch({ type: 'comment/commentRewardList'})
            }
        }
		return (
			<div className="fadeIn animated">
			<CommentList {...listData}>
			  <div className="yaloutList">
			   <h3>评论打赏列表</h3>
			   <ul className="lis">
			   {
			   	commentReward.length!=0?
			   	commentReward.map((d,i)=><li key={i}><span><img src={d.headimgurl}/></span>{d.nickname}{d.money!=0?<font>打赏&nbsp;{d.money}&nbsp;元</font>:null}</li>)
			   	:null
			   }
			    
			   </ul>
			    {
                    commentReward.length != 0 ?
                    <div className="moreStyle">{commentLength ? loading.effects['comment/commentRewardList'] ? '正在加载' : '加载更多' : '没有更多打赏了'}</div>
                    : null
                }
			  </div>
			</CommentList>
			</div>
		);
	}
}

export default connect(mapStateToProps)(RewardList)

