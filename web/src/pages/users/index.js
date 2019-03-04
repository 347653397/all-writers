
import './index.less';
import { connect } from 'dva';
import { List,Badge, ActivityIndicator } from 'antd-mobile';
import { routerRedux } from 'dva/router';
/**
*--用户模块
*--2018.07.13 anaoei@qq.com
* @method Index 文件名
* @param { ReactElement } userPage 组件名
* @param { String } users model名称
*/
function mapStateToProps(props) {
  return {
    users: props.users,
    loading: props.loading
  };
}
const Item = List.Item;
const userPage = ({ dispatch, loading, users }) => {
  let { userData } = users;
  const routerPush = (routerName, query) => {
    dispatch(routerRedux.push({
      pathname: routerName,
      query
    }));
  }

  return (
    <div className="centerUserBox">
      <div className="fadeIn animated">
        <div className="top">
          <div className="userImg"><img src={userData.headimgurl} /></div>
          <h3>{userData.nickname || '--'}</h3>
          {
            userData.mobile ?
              <p>{userData.mobile || '--'}</p>
              :
              <p style={{ padding: '0.3rem' }} onClick={() => routerPush('/login')}>绑定手机号</p>
          }

        </div>
        <List className="list">
          {/* extra={<div className="moneny">{userData.cash_balance || '0'}元{userData.cash_balance != 0 ? <span onClick={() => Boolean(userData.mobile) ? routerPush('/users/forward') : routerPush('/login')}>提现</span> : null}</div>} */}
          <Item onClick={() => Boolean(userData.mobile) ? routerPush('/users/detai') : routerPush('/login')} extra={<div className="moneny">{userData.cash_balance || '0'}元</div>} arrow="horizontal" extra={<Badge dot={userData.wallet_dot==1?true:false} />}>我的钱包</Item>
          <Item arrow="horizontal" onClick={() => routerPush('/users/order')} extra={<Badge dot={userData.order_dot==1?true:false} />}>我的订单</Item>
          <Item arrow="horizontal" onClick={() => routerPush('/users/comment')} extra={<Badge dot={userData.comment_dot==1?true:false} />}>我的评论</Item>
          <Item arrow="horizontal" onClick={() => routerPush('/users/submission')} extra={<Badge dot={userData.contribute_dot==1?true:false} />}>我的投稿</Item>
           <Item arrow="horizontal" onClick={() => routerPush('/users/auction')} extra={<Badge dot={userData.auction_dot==1?true:false} />}>我的竞拍</Item>
          <Item arrow="horizontal" onClick={() => routerPush('/users/proposal')} >意见建议</Item>
          {/* <Item arrow="horizontal" onClick={() => routerPush('/users/help')}>帮助中心</Item> */}
        </List>
        <ActivityIndicator
          toast
          text="加载数据"
          animating={loading.effects['users/userCenter']}
        />
      </div>
    </div>
  )
}
export default connect(mapStateToProps)(userPage)
