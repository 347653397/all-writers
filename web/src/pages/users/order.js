import { connect } from 'dva';
import { Modal, ActivityIndicator } from 'antd-mobile';
import { routerRedux } from 'dva/router';
import Comment from '../../components/comment';
import Commentsub from '../detai/components/comment';
import './index.less';
const alert = Modal.alert;

/**
*--订单模块
*--2018.07.13 anaoei@qq.com
* @method Order 文件名
* @param { ReactElement } Order 组件名
* @param { String } users model名称
*/
function mapStateToProps(props) {
    return {
        users: props.users,
        loading: props.loading
    };
}
const Order = ({ users, dispatch, loading }) => {
    const { orderData, orderSel } = users;
    const pageChangeHandler = (id, routerName) => {
        dispatch(routerRedux.push({
            pathname: routerName,
            query: { id,in_type:1 }
        }));
    }
    return (
        <div>
            <div className="orderBox">
                <ActivityIndicator
                    toast
                    text="加载数据"
                    animating={loading.effects['users/myOrderlist']}
                />
                <ul>
                    {
                        orderData && orderData.map((d, i) => {
                            return (
                                <li key={i}>
                                    <div className="orderNumber">订单编号：{d.order_num}</div>
                                    <div onClick={() => pageChangeHandler(d.up_type == 2 ? d.course_id : d.item_id, d.up_type == 2 ? '/detai/list' : '/detai')}>
                                        <div className="left">
                                            <img src={d.course_pic} />
                                        </div>
                                        <div className="right">
                                            <h3>{d.course_title}</h3>
                                            <p><span>{d.author}-已购买({d.up_type == 1 ? '单集' : '连载'})</span></p>
                                        </div>
                                    </div>
                                    <div className="orderBtn">
                                        {
                                            Number(d.pay_status) != 2 ?
                                                <span className="color1" onClick={() => alert('删除提示', '确认要删除该商品吗', [
                                                    { text: '取消', onPress: () => console.log('cancel'), style: 'default' },
                                                    { text: '确定', onPress: () => dispatch({ type: 'users/deleteFailOrder', payload: { order_id: d.order_id } }) },
                                                ])}>删除</span>
                                                : null
                                        }
                                        {/* {
                                            Number(d.pay_status) == 2 ?
                                                Number(d.is_comment == 0) ?
                                                    <span className="color2" onClick={() => {
                                                        // dispatch({ type: 'detai/getContentItems', payload: { course_id: d.course_id } })
                                                        dispatch({ type: 'detai/save', payload: { commentVisible: true } })
                                                        dispatch({ type: 'users/save', payload: { orderSel: d } })
                                                    }}>评价</span>
                                                    : <span>已评价</span>
                                                : null
                                        } */}
                                        <span>{(() => {
                                            switch (Number(d.pay_status)) {
                                                case 2:
                                                    return '已支付'
                                                    break;
                                                default:
                                                    return '未支付'
                                                    break;
                                            }
                                        })()}</span>
                                    </div>
                                </li>
                            )
                        })
                    }
                </ul>
                {
                    orderData && orderData.length == 0 ?
                        <div>
                            <div className="lengthIcon"></div>
                            <p className="lengthIcontext">暂无订单记录</p>
                        </div>
                        : null
                }
                {/* 写评论 */}
                <Commentsub popup={false} commentSubmit={(val, roteVal) => {
                    dispatch({
                        type: 'detai/comment',
                        payload: {
                            course_items_id: orderSel.item_id,
                            content: val,
                        },
                        getLoading: () => dispatch({ type: 'users/myOrderlist' })
                    })
                }} />
            </div>
        </div>
    )
}

export default connect(mapStateToProps)(Order)