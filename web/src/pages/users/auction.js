import { connect } from 'dva';
import { routerRedux } from 'dva/router';
import { Component } from 'react';
import { getDateDiff } from '../../utils'
import CommentList from '../../components/list'
import { Modal,SwipeAction } from 'antd-mobile';
const alert = Modal.alert;
/**
*--竞拍模块
*--2018.07.13 anaoei@qq.com
* @method Auction 文件名
* @param { ReactElement } Auction 组件名
* @param { String } users model名称
*/
function mapStateToProps(props) {
    return {
        users: props.users,
        loading:props.loading
    };
}

class Auction extends Component{
    componentDidMount() {
       this.props.dispatch({ type: 'users/myCourse'});
       this.props.dispatch({type:'release/save',payload:{releaseRouter:'/users/auction'}})
    }

    componentWillUnmount() {
      this.props.dispatch({
        type:'users/save',
        payload:{
            subLength:true,
            subData:[],
            subTotal:0,
            subPageNum:1,
        }
      })
    }
    //编辑
    onEdit(id){
    	this.props.dispatch(routerRedux.push({
            pathname: '/release',
            query: {id }
        }))
    }
    //删除
    onDel(id,index){
    	alert('操作提示', '确定要删除吗?', [
          { text: '取消', onPress: () => console.log('cancel') },
          { text: '删除', onPress: () => this.props.dispatch({type:'users/delCourse',payload:{item_id:id,index}}) },
        ])
    }
	render(){
		const {dispatch,users,loading}=this.props;
		const pageChangeHandler = (id, routerName) => {
        dispatch(routerRedux.push({
	            pathname: routerName,
	            query: { id, in_type:2}
	        }));
	    }
	    const {subData,subLength,}=users
	    const listData={
			actionType:'users/save',
            loading:loading.effects['users/myCourse'],
            listLength:subLength,
            onChangeGet:()=>{
                 dispatch({ type: 'users/myCourse'})
            }
        }
        // onClick={() => pageChangeHandler(d.up_type == 2 ? d.course_id : d.item_id, d.up_type == 2 ? '/detai/list' : '/detai')}
    return (
        <div className="listBox listTab2">
           <CommentList {...listData}>
            <ul>
                {
                    subData && subData.map((d, i) => {
                        return (
                            <li key={i} >
                               <SwipeAction
							      autoClose
							      disabled={d.auction_status==3?false:true}
							      right={[
							        {
							          text: '编辑',
							          onPress: () =>this.onEdit(d.id) ,
							          style: { backgroundColor: '#FFBE30', color: '#fff' },
							        },
							        {
							          text: '删除',
							          onPress: () =>this.onDel(d.id,i) ,
							          style: { backgroundColor: '#FF5555', color: '#fff' },
							        },
							      ]}
							    >
							    <div>
                                <div className="left" onClick={() => pageChangeHandler(d.up_type == 2 ? d.course_id : d.id, d.up_type == 2 ? '/detai/list' : '/detai')}>
                                    <img src={d.audio_pic} />
                                </div>
                                <div className="right">
                                    <h3 className="textOversTab2" onClick={() => pageChangeHandler(d.up_type == 2 ? d.course_id : d.id, d.up_type == 2 ? '/detai/list' : '/detai')}>{d.up_type == 2 ? <span className="color1">连载</span> : <span className="color2">单集</span>}{d.title}</h3>
                                    <p className="textOversTab2">{d.audio_brief || '暂无介绍'}</p>
                                    <p >
                                      {(()=>{
                                    	switch(Number(d.auction_status)){
                                    		case 1: return <font className="price" style={{ float: 'right', fontSize: '0.35rem' }} onClick={()=>dispatch({type:'users/applyAuction',payload:{item_id:d.id,index:i}})}>申请竞拍></font>
                                            break;
                                    		case 2:return <font className="price" style={{ float: 'right', fontSize: '0.35rem' }}>待竞拍审核</font>;
                                    		break;
                                    		case 3:return <font className="price" style={{ float: 'right',color:'red', fontSize: '0.35rem' }} onClick={()=>alert('未通过原因', d.audit_fail, [
									          { text: '取消', onPress: () => console.log('cancel') },
									          { text: '去编辑', onPress: () => this.onEdit(d.id)},
									        ])}>查看分析></font>;
                                    		break;
                                    		case 4:return <font className="price" style={{ float: 'right', fontSize: '0.35rem' }}>竞拍中</font>;
                                    		break;
                                    		default:return <font className="price" style={{ float: 'right',color:'red',  fontSize: '0.35rem' }}>竞拍结束</font>;
                                    		break;
                                    	}
                                    })()}
                                    </p>
                                    <div className="tags">
                                        <p>{new Date(Number(d.create_time*1000)).Format("MM-dd hh:mm")}</p>
                                    </div>
                                </div>
                                </div>
                                </SwipeAction>
                            </li>
                        )
                    })
                }
                {
                    subData && subData.length == 0 && !loading.effects['users/myCourse'] ?
                         <div>
                        <div className="lengthIcon"></div>
                        <p className="lengthIcontext">暂无竞拍</p>
                       </div>
                        : null
                }
            </ul>
            </CommentList>
            <div className="moreStyle">{subData.length != 0 ? subLength ? loading.effects['users/myCourse'] ? '正在加载' : '加载更多' : '没有更多了' : ''}</div>
        </div>
    )
	}
}

export default connect(mapStateToProps)(Auction)