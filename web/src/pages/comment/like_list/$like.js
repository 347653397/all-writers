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

export class LikeList extends React.Component {
	// static propTypes = {
	// 	name: React.PropTypes.string,
	// };
    componentDidMount() {
        console.log(this.props.match.params.like)
        this.props.dispatch({ type: 'comment/save',payload:{commentId:this.props.match.params.like} });
        this.props.dispatch({ type: 'comment/commentLikeList',payload:{comment_id:this.props.match.params.like} });
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
        }
      })
    }
	render() {
		const {loading,dispatch}=this.props;
		const {commentLike,commentLength,commentId}=this.props.comment;
		const listData={
			actionType:'comment/save',
            loading:loading.effects['comment/commentLikeList'],
            listLength:commentLength,
            onChangeGet:()=>{
                 dispatch({ type: 'comment/commentLikeList'})
            }
        }
		return (
			<div className="fadeIn animated">
			<CommentList {...listData}>
			  <div className="yaloutList">
			   <h3>评论点赞列表</h3>
			   <ul className="lis">
			   {
			   	commentLike.length!=0?
			   	commentLike.map((d,i)=><li key={i}><span><img src={d.headimgurl}/></span>{d.nickname}</li>)
			   	:null
			   }
			    
			   </ul>
			    {
                    commentLike.length != 0 ?
                    <div className="moreStyle">{commentLength ? loading.effects['comment/commentLikeList'] ? '正在加载' : '加载更多' : '没有更多点赞了'}</div>
                    : null
                }
                
			  </div>
			</CommentList>
			</div>
		);
	}
}

export default connect(mapStateToProps)(LikeList)

